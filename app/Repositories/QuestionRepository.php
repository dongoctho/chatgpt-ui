<?php

namespace App\Repositories;

use App\Models\Question;
use Illuminate\Support\Collection;

class QuestionRepository extends BaseRepository
{
    public function __construct(Question $question)
    {
        parent::__construct($question);
    }

    /**
     * Get question and answer by thread id
     * 
     * @param int $threadId 
     * @param int $questionId 
     * @return Collection 
     */
    public function getQuestionAnswersGtQuestionId(int $threadId, int $questionId = 0): Collection
    {
        return $this->model
            ->with('answers')
            ->where('thread_id', $threadId)
            ->where('id','>', $questionId)
            ->get();
    }
}
