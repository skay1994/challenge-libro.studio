<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UserRepository;

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
}
