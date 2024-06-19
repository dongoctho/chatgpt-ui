<?php

namespace App\Repositories;

use OpenAI;

class AssistantRepository
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
     * Create new Assistant
     * 
     * @param string|null $instructions 
     * @param string $model 
     * @return array 
     */
    public function create(string $model, string $instructions = null): array
    {
        $assistant = $this->client->assistants()->create([
            'instructions' => $instructions,
            'model' => $model
        ]);

        return $assistant->toArray();
    }

    /**
     * Update infomation for Assistant
     * 
     * @param string $assistantId 
     * @param string $instructions 
     * @param string $model 
     * @return void 
     */
    public function update(string $assistantId, string $model, string $instructions = null): void
    {
        if ($instructions != null) {
            $this->client->assistants()->modify($assistantId, [
                'instructions' => $instructions,
                'model' => $model
            ]);
        } else {
            $this->client->assistants()->modify($assistantId, [
                'model' => $model
            ]);
        }
    }

}
