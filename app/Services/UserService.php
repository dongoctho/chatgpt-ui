<?php

namespace App\Services;

use App\Repositories\UserRepository;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users
     *
     * @return Collection
     */
    public function getUser()
    {
        return $this->userRepository->all();
    }

    /**
     * Get a user by their ID
     *
     * @param int $id
     * @return User
     */
    public function getUserById($id)
    {
        return $this->userRepository->find($id);
    }

    /**
     * Create a new user
     *
     * @param array $attributes
     * @return User
     */
    public function createUser(array $attributes)
    {
        return $this->userRepository->create($attributes);
    }

    /**
     * Update a user
     *
     * @param array $attributes
     * @param int $id
     * @return User
     */
    public function updateUser(array $attributes, $id)
    {
        return $this->userRepository->update($attributes, $id);
    }
    
    /**
     * Delete a user
     *
     * @param int $id
     * @return void
     */

    public function deleteUser($id)
    {
        return $this->userRepository->delete($id);
    }
}
