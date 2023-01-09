<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Repositories\Contracts\{CourseRepositoryContract, UserRepositoryContract};
use App\Repositories\{CourseRepository, UserRepository};

class RepositoryServiceProvider extends ServiceProvider
{
    private array $repositories = [
        CourseRepositoryContract::class => CourseRepository::class,
        UserRepositoryContract::class => UserRepository::class
    ];

    public function register()
    {
        foreach ($this->repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }
}
