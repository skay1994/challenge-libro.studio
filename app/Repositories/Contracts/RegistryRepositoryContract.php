<?php

namespace App\Repositories\Contracts;
interface RegistryRepositoryContract
{
    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function remove(array $data);
}
