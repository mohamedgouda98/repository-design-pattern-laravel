<?php


namespace Unlimited\Repository\Console;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Unlimited\Repository\Console\Traits\CommandHelper;

class CreateRepository extends Command
{
    use CommandHelper;

    protected $signature="repository:create {name}";

    protected $description="Create New Repository";

    public $files;


    public function handle(Filesystem $filesystem)
    {
        $this->files = $filesystem;

        $name = $this->argument('name');

        $repositoriesPath = base_path() . '/app/http/Repositories';
        $InterfacesPath = base_path() . '/app/http/Interfaces';

        $this->createInterfacesFolder($InterfacesPath);
        $this->createRepositoriesFolder($repositoriesPath);

        $InterfaceName= $name . 'Interface';
        $this->createInterfaceFile($InterfaceName);

        $repositoryName= $name . 'Repository';
        $this->createRepositoryFile($repositoryName, $InterfaceName);

        $controllerName= $name .'Controller';
        $this->createControllerFile($controllerName);
    }

}