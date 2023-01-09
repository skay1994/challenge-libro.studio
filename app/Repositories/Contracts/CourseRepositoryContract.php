<?php

namespace App\Repositories\Contracts;

use App\Models\Course;

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

    /**
     * @param Course $course
     * @param array $data
     * @return mixed
     */
    public function update(Course $course, array $data);
}
