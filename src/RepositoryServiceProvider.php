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
    $this->app->bind(
        'App\Http\Interfaces\admin/CountryReInterface',
        'App\Http\Repositories\admin/CountryReRepository'
        );

    $this->app->bind(
        'App\Http\Interfaces\CountryInterface',
        'App\Http\Repositories\CountryRepository'
        );

    $this->app->bind(
        'App\Http\Interfaces\GoudInterface',
        'App\Http\Repositories\GoudRepository'
        );

    $this->app->bind(
        'App\Http\Interfaces\GoudInterface',
        'App\Http\Repositories\GoudRepository'
        );

    $this->app->bind(
        'App\Http\Interfaces\GoudUpInterface',
        'App\Http\Repositories\GoudUpRepository'
        );

    $this->app->bind(
        'App\Http\Interfaces\GoudKLInterface',
        'App\Http\Repositories\GoudKLRepository'
        );

//Bind
    }

}