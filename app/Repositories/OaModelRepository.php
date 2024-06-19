<?php

namespace App\Repositories;

use App\Models\OAModel;

class OaModelRepository extends BaseRepository
{
    public function __construct(OAModel $oaModel)
    {
        parent::__construct($oaModel);
    }
}
