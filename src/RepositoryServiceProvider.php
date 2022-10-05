<?php


namespace Unlimited\Repository;


use Illuminate\Support\ServiceProvider;
use Unlimited\Repository\Console\CreateRepository;

class RepositoryServiceProvider extends ServiceProvider
{

    public function boot()
    {

        if($this->app->runningInConsole())
        {
            $this->commands([
                CreateRepository::class,
            ]);
        }


    }

    public function register()
    {
        $this->app->bind('repository', function(){
            return new HelloClass;
        });
    }

}