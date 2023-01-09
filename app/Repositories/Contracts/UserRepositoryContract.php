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
}
