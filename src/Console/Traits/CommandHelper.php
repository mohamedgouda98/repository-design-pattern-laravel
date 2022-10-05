<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

trait CommandHelper
{
    protected function createInterfacesFolder($InterfacesPath)
    {
        if (File::exists($InterfacesPath)) {
            $this->info('Interfaces folder already exists. Skipping.');

            return;
        }

        if (! File::makeDirectory($InterfacesPath, 0755, true)) {
            throw new RuntimeException('Cannot create Interfaces folder');
        }

        $this->info('Interfaces folder was successfully created.');
    }

    protected function createRepositoriesFolder($RepositoriesPath)
    {
        if (File::exists($RepositoriesPath)) {
            $this->info('Repositories folder already exists. Skipping.');

            return;
        }

        if (! File::makeDirectory($RepositoriesPath, 0755, true)) {
            throw new RuntimeException('Cannot create Repositories folder');
        }

        $this->info('Repositories folder was successfully created.');
    }

    public function createInterfaceFile($InterfaceName)
    {
        $sourceFile =  __DIR__ . "/../../stubs/RepositoryInterface.php.stub";
        $copyPath = base_path() . '/app/http/Interfaces/';

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryInterface', $InterfaceName, $contents);

        $this->files->put($copyPath . $InterfaceName.'.php', $new_contents);

        $this->info('Repository Interface was successfully created.');
    }

    public function createRepositoryFile($repositoryName, $InterfaceName)
    {
        $sourceFile =  __DIR__ . "/../../stubs/RepositoryClass.php.stub";
        $copyPath = base_path() . '/app/http/Repositories/';

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryClass', $repositoryName, $contents);
        $new_contents = str_replace('RepositoryInterface', $InterfaceName, $new_contents);

        $this->files->put($copyPath . $repositoryName.'.php', $new_contents);

        $this->info('Repository Class was successfully created.');

    }

    public function createControllerFile($controllerName)
    {
        Artisan::call('make:controller ' . $controllerName);
        $this->info('Controller class was successfully created.');
    }



}