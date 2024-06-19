<?php

namespace App\Repositories;

use OpenAI;

class MessageRepository
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
     * Create a new message.
     * 
     * @param string $threadId 
     * @param string $prompt 
     * @return void 
     */
    public function create(string $threadId, string $prompt): void
    {
        $this->client->threads()->messages()->create($threadId, [
            'role' => 'user',
            'content' => $prompt
        ]);
    }
}
