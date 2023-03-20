<?php


namespace Unlimited\Repository;


use Illuminate\Support\ServiceProvider;
use Unlimited\Repository\Console\CreateRepository;
use Unlimited\Repository\Console\Traits\ProviderHelper;

class RepositoryServiceProvider extends ServiceProvider
{
    use ProviderHelper;

    public function boot()
    {

        if($this->app->runningInConsole())
        {
            $this->commands([
                CreateRepository::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('RepositoryDesignPattern.php'),
        ], 'repository-pattern-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'RepositoryDesignPattern');

        $interfacePath =(base_path() . '/app/Http/Interfaces');
        $repositoriesPath =(base_path() . '/app/Http/Repositories');

        $originInterfacesFileName = $this->getFilesListInDirectory($interfacePath, '-13');
        $originRepositoriesFileName = $this->getFilesListInDirectory($repositoriesPath, '-14');

        $this->bindFiles($originInterfacesFileName, $originRepositoriesFileName);

    }

}