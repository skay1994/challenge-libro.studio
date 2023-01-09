<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Exception;

use App\Repositories\Contracts\RegistryRepositoryContract;

use App\Models\Course;

class RegistryRepository implements RegistryRepositoryContract
{
    public function add(array $data)
    {
        DB::beginTransaction();
        try {
            /** @var Course $course */
            $course = Course::find($data['course_id']);
            $course->users()->attach($data['user_id']);

            DB::commit();
            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function remove(array $data)
    {
        DB::beginTransaction();
        try {
            /** @var Course $course */
            $course = Course::find($data['course_id']);
            $course->users()->detach($data['user_id']);

            DB::commit();
            return response()->json([
                'success' => true,
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
