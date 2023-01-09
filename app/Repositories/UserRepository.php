<?php

namespace App\Repositories;

use App\Repositories\Contracts\UserRepositoryContract;

use App\Models\User;

use App\Http\Resources\UserListResource;


class UserRepository implements UserRepositoryContract
{
    public function getAll(array $data)
    {
        $query = User::query();
        if(isset($data['search']))
            $query->search($data['search']);

        return UserListResource::collection($query->paginate($data['limit'] ?? 10));
    }
}
