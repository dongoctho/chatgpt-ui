<?php

namespace App\Repositories;

use OpenAI;
use OpenAI\Responses\Threads\Runs\ThreadRunResponse;

class RunRepository
{
    private $key;
    private $client;
    
    /**
     * Create a new class instance.
     */
    public function __construct() 
    {
        $this->key = config('handle.key');
        $this->client = OpenAI::client($this->key);
    }

    /**
     * Create a new run.
     * 
     * @param string $threadId 
     * @param string $assistantId 
     * @return ThreadRunResponse 
     */
    public function create(string $threadId, string $assistantId)
    {
        return $this->client->threads()->runs()->create(
            threadId: $threadId,
            parameters: [
                'assistant_id' => $assistantId
            ]
        );
    }

    /**
     * Find run by id
     * 
     * @param string $threadId 
     * @param string $id 
     * @return ThreadRunResponse 
     */
    public function find(string $threadId, string $id)
    {
        return $this->client->threads()->runs()->retrieve(
            threadId: $threadId,
            runId: $id
        );
    }
}
