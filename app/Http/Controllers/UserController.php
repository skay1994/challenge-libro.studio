<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Repositories\UserRepository;

use App\Http\Requests\StoreUserRequest;

use App\Http\Resources\UserResource;

use App\Models\User;

class UserController extends Controller
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function index(Request $request)
    {
        $data = $request->only(['limit', 'search']);
        return $this->repository->getAll($data);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->safe()->all();
        return $this->repository->store($data);
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function update(StoreUserRequest $request, User $user)
    {
        $data = $request->safe()->all();
        return $this->repository->update($user, $data);
    }
}
