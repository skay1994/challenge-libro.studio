<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Repositories\Contracts\UserRepositoryContract;
use Illuminate\Support\Facades\DB;
use Exception;

use App\Models\User;

use App\Http\Resources\UserListResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserRepository implements UserRepositoryContract
{
    public function getAll(array $data)
    {
        $query = User::query();
        if(isset($data['search']))
            $query->search($data['search']);

        return UserListResource::collection($query->paginate($data['limit'] ?? 10));
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $data['password'] = Hash::make(Str::random());
            $user = User::create($data);

            if(isset($data['course_id'])) {
                $user->courses()->sync($data['course_id']);
                $user->load('courses');
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => new UserResource($user)
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(User $user, array $data)
    {
        DB::beginTransaction();

        try {
            $user->update($data);

            if(isset($data['course_id'])) {
                $user->courses()->sync($data['course_id']);
                $user->load('courses');
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => new UserResource($user)
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
