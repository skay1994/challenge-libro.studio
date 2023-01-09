<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Repositories\Contracts\{CourseRepositoryContract};
use App\Repositories\{CourseRepository};

class RepositoryServiceProvider extends ServiceProvider
{
    private array $repositories = [
        CourseRepositoryContract::class => CourseRepository::class,
    ];

    public function register()
    {
        foreach ($this->repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }
}
