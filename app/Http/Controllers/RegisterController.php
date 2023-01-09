<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddCourseUserRequest;
use App\Repositories\RegistryRepository;

class RegisterController extends Controller
{
    public function __construct(private RegistryRepository $repository)
    {
    }

    public function add(AddCourseUserRequest $request)
    {
        return $this->repository->add($request->safe()->all());
    }

    public function remove(AddCourseUserRequest $request)
    {
        return $this->repository->remove($request->safe()->all());
    }
}
