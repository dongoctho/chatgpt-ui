<?php

namespace App\Repositories;

use App\Models\Division;

class DivisionRepository extends BaseRepository
{
    /**
     *
     * @param Division $divivison
     * @return void
     */
    public function __construct(Division $divivison)
    {
        parent::__construct($divivison);
    }

    /**
     *
     * @param mixed $name
     * @return mixed
     */
    public function getDivisionByName($name)
    {
        return $this->model->where('name', $name)->first();
    }
}
