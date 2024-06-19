<?php

namespace App\Repositories;

use App\Models\Thread;
use OpenAI;

class ThreadRepository extends BaseRepository
{
    private $key;
    private $client;

    public function __construct(Thread $thread)
    {
        parent::__construct($thread);
        $this->key = config('handle.key');
        $this->client = OpenAI::client($this->key);
    }

    /**
     * Get all thread sort by created
     *
     * @return mixed
     */
    public function getThreadsByUserId($userId)
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }

    /**
     * Create new Thread
     *
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        $title = $attributes['title'];
        $userId = $attributes['userId'];
        $model = $attributes['model_id'];

        $threads = $this->client->threads()->create([]);

        return $this->model->create([
            'title' => $title,
            'oa_thread_id' => $threads['id'],
            'assistant_id' => $attributes['assistant_id'],
            'user_id' => $userId,
            'model_id' => $model
        ]);
    }

    /**
     *
     * @param string $threadId
     * @return
     */
    public function list(string $threadId)
    {
        return $this->client->threads()->messages()->list($threadId)->toArray();
    }
}
