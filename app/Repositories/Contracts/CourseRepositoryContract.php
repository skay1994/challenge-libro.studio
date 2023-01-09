<?php

namespace App\Repositories\Contracts;

interface CourseRepositoryContract
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
