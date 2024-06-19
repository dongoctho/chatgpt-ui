<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateTitleRequest;
use App\Services\OpenAiService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class OpenAiController extends Controller
{
    protected $openAiService;

    /**
     * Constructs a new OpenAiService
     *
     * @param OpenAiService $openAiService
     */
    public function __construct(OpenAiService $openAiService)
    {
        $this->openAiService = $openAiService;
    }

    /**
     * Get list thread
     *
     * @return JsonResponse
     */
    public function index()
    {
        try {
            $userId = Auth::user()->id;
            return response()->json($this->openAiService->listThread($userId), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Find thread by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function findThread(int $id)
    {
        try {
            return response()->json($this->openAiService->findThread($id), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a thread by id
     *
     * @param int $id
     * @return JsonResponse
     */
    public function deleteThread(int $id)
    {
        try {
            $this->openAiService->deleteThread($id);

            return response()->json(['success' => 'Delete successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update title of a thread by id
     *
     * @param UpdateTitleRequest $request
     * @param int $threadId
     * @param int $id
     * @return JsonResponse
     */
    public function renameThread(UpdateTitleRequest $request, int $threadId)
    {
        try {
            $this->openAiService->updateTitleThread($threadId, $request->title);

            return response()->json(['success' => 'Rename Thread successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get list model
     *
     * @return JsonResponse
     */
    public function getModels()
    {
        try {
            return response()->json($this->openAiService->listOAModels(), Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create thread
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function createThread(Request $request)
    {
        try {

            $instructions = $request->instructions;
            $model = $request->model;
            $title = $request->title;
            $userId = Auth::user()->id;
            $thread = $this->openAiService->createThread($title, $model, $userId, $instructions);

            return response()->json($thread, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Create chat
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function storeChat(Request $request, int $id)
    {
        try {
            $prompt = $request->prompt;
            $model = $request->model;
            $instructions = $request->instructions;
            $chats = $this->openAiService->chat($id, $prompt, $model, $instructions);

            return response()->json($chats, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get question answer by threadId
     *
     * @param int $threadId
     * @param Request $request
     * @return JsonResponse
     */
    public function getChats(int $threadId, Request $request)
    {
        $lastQuestionId = $request->query('lastQuestionId', 0);
        $chats = $this->openAiService->getQuestionAnswer($threadId, $lastQuestionId);

        return response()->json($chats, Response::HTTP_OK);
    }
}
