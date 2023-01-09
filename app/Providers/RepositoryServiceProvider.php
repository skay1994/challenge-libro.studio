<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    private array $repositories = [];

    public function register()
    {
        foreach ($this->repositories as $contract => $repository) {
            $this->app->bind($contract, $repository);
        }
    }
}
