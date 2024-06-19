<?php

namespace App\Services;

use App\Repositories\AnswerRepository;
use App\Repositories\AssistantRepository;
use App\Repositories\MessageRepository;
use App\Repositories\OaModelRepository;
use App\Repositories\OpenAiRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\RunRepository;
use App\Repositories\ThreadRepository;
use DB;
use Illuminate\Http\JsonResponse;
use Log;

class OpenAiService
{
    protected $openAiRepository;
    protected $threadRepository;
    protected $assistantRepository;
    protected $questionRepository;
    protected $answerRepository;
    protected $oaModelRepository;
    protected $messageRepository;
    protected $runRepository;


    /**
     * Define repository
     *
     * @param ThreadRepository $threadRepository
     * @param QuestionRepository $questionRepository
     * @param AnswerRepository $answerRepository
     * @return void
     */
    public function __construct(
        ThreadRepository $threadRepository,
        AssistantRepository $assistantRepository,
        QuestionRepository $questionRepository,
        AnswerRepository $answerRepository,
        OaModelRepository $oaModelRepository,
        MessageRepository $messageRepository,
        RunRepository $runRepository
    ) {
        $this->threadRepository = $threadRepository;
        $this->assistantRepository = $assistantRepository;
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->oaModelRepository = $oaModelRepository;
        $this->messageRepository = $messageRepository;
        $this->runRepository = $runRepository;

        define('THREAD_RUN_MAX_TIME_EXEC', config('handle.thread_run_max_time_exec'));
    }

    /**
     * Get all thread
     *
     * @return mixed
     */
    public function listThread($userId)
    {
        return $this->threadRepository->getThreadsByUserId($userId);
    }

    /**
     * Get all question and answer by thread id
     *
     * @param int $id
     * @return mixed
     */
    public function getQuestionAnswer(int $threadId, int $questionId)
    {
        return $this->questionRepository->getQuestionAnswersGtQuestionId($threadId, $questionId);
    }

    /**
     * Get thread by id
     *
     * @param int $id
     * @return mixed
     */
    public function findThread(int $id)
    {
        return $this->threadRepository->find($id);
    }

    /**
     * Delete thread
     *
     * @param int $id
     * @return bool
     */
    public function deleteThread(int $id)
    {
        try {
            $this->threadRepository->delete($id);
        } catch (\Exception $e) {
            Log::error($e);

            return $e->getMessage();
        }
    }

    /**
     * Rename thread
     *
     * @param int $id
     * @param string $title
     * @return JsonResponse
     */
    public function updateTitleThread(int $id, string $title)
    {
        DB::beginTransaction();
        try {
            $this->threadRepository->update(
                [
                    'title' => $title
                ],
                $id
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return $e->getMessage();
        }
    }

    /**
     * Get model by id
     *
     * @param int $id
     * @return mixed
     */
    public function findOAModels(int $id)
    {
        return $this->oaModelRepository->find($id);
    }

    /**
     * Get all model
     *
     * @return mixed
     */
    public function listOAModels()
    {
        return $this->oaModelRepository->all();
    }

    /**
     * Create thread
     *
     * @param string $title
     * @param string $model
     * @param int $userId
     * @param string|null $instructions
     * @return mixed
     */
    public function createThread(string $title, string $model, int $userId, string $instructions = null)
    {
        try {
            $oaModel = $this->oaModelRepository->find($model)->name;
            $assistant = $this->assistantRepository->create($oaModel, $instructions);
            $threads = $this->threadRepository->create([
                'title' => $title,
                'userId' => $userId,
                'model_id' => $model,
                'assistant_id' => $assistant['id'],
            ]);

            return $threads;
        } catch (\Exception $e) {
            Log::error($e);

            return $e->getMessage();
        }
    }

    /**
     * Create chat
     *
     * @param int $id
     * @param string $prompt
     * @param int $model
     * @param string|null $instructions
     * @return array
     */
    public function chat(int $id, string $prompt, int $model, string $instructions = null)
    {
        $thread = $this->threadRepository->find($id);
        $threadId = $thread->oa_thread_id;
        $assistantId = $thread->assistant_id;
        $oaModel = $this->oaModelRepository->find($model)->name;
        $startTime = time();

        $this->threadRepository->update(['model_id' => $model], $id);
        $this->assistantRepository->update($assistantId, $oaModel, $instructions);
        $this->messageRepository->create($threadId, $prompt);
        $runs = $this->runRepository->create($threadId, $assistantId);

        while (true) {
            $runsRetrive = $this->runRepository->find($threadId, $runs->id);
            if ($runsRetrive->status === 'completed') break;
            if (time() - $startTime > THREAD_RUN_MAX_TIME_EXEC) break;
        }

        $response = $this->threadRepository->list($threadId);
        $dataOa = $response['data'][0];
        $gptKey = ['content', 0, 'text', 'value'];
        $question = $this->questionRepository->create([
            'thread_id' => $id,
            'content' => $prompt,
        ]);
        $this->answerRepository->create([
            'question_id' => $question->id,
            'content' => get_value_from_array($dataOa, $gptKey)
        ]);

        return [
            'thread_id' => $id,
            'question_id' => $question->id,
        ];
    }
}
