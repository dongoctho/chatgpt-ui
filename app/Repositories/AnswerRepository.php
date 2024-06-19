<?php

namespace App\Repositories;

use App\Models\Answer;

class AnswerRepository extends BaseRepository
{
    public function __construct(Answer $answer)
    {
        parent::__construct($answer);
    }
}
