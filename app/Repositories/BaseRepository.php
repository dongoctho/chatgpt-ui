<?php

namespace App\Repositories;

abstract class BaseRepository
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * Get all items from the database
     *
     * @return Collection
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * Find an item by its ID
     *
     * @param int $id The ID of the item to find
     * @return Model|null The found item, or null if not found
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * create 
     * @param array $attributes 
     */
    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    /**
     * Update an existing item in the database
     *
     * @param array $attributes The attributes of the item to update
     * @param int $id The ID of the item to update
     * @return Model The updated item
     */
    public function update(array $attributes, $id)
    {
        return $this->model->find($id)->update($attributes);
    }

    /**
     * Delete an existing item from the database
     *
     * @param int $id The ID of the item to delete
     * @return bool True if the item was deleted, false if not found
     */
    public function delete($id)
    {
        return $this->model->find($id)->delete();
    }
}
