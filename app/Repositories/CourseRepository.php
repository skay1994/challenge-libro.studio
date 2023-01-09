<?php

namespace App\Repositories;

use App\Http\Resources\CourseListResource;
use Exception;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryContract;

class CourseRepository implements CourseRepositoryContract
{
    public function getAll(array $data)
    {
        $query = Course::query();
        if(isset($data['search']))
            $query->search($data['search']);

        return CourseListResource::collection($query->paginate($data['limit'] ?? 10));
    }
}
