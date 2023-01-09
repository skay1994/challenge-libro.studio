<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryContract
{
    /**
     * @param array $data
     * @return mixed
     */
    public function getAll(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param User $user
     * @param array $data
     * @return mixed
     */
    public function update(User $user, array $data);

    /**
     * @param User $user
     * @return mixed
     */
    public function destroy(User $user);
}
