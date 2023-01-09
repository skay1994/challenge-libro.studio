<?php

namespace App\Repositories;

use App\Http\Resources\CourseListResource;
use Exception;

use App\Models\Course;
use App\Repositories\Contracts\CourseRepositoryContract;
use Illuminate\Support\Facades\DB;

class CourseRepository implements CourseRepositoryContract
{
    public function getAll(array $data)
    {
        $query = Course::query();
        if(isset($data['search']))
            $query->search($data['search']);

        return CourseListResource::collection($query->paginate($data['limit'] ?? 10));
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $course = Course::create($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $course
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
