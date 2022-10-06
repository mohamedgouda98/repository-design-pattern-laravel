<?php


namespace Unlimited\Repository\Console;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Unlimited\Repository\Console\Traits\CommandHelper;
use Unlimited\Repository\Console\Traits\ProviderHelper;

class CreateRepository extends Command
{
    use CommandHelper;
    use ProviderHelper;

    protected $signature="repository:create {name}";

    protected $description="Create New Repository";

    public $files;

    public $isExists = false;


    public function handle(Filesystem $filesystem)
    {
        $this->files = $filesystem;

        $name = $this->argument('name');

        $repositoriesPath = base_path() . '/app/http/Repositories';
        $interfacesPath = base_path() . '/app/http/Interfaces';

        $interfacesName= $name . 'Interface';
        $repositoryName= $name . 'Repository';

        $this->isExists = $this->isExistsFiles($interfacesPath, $interfacesName);
        if($this->isExists == true)
        {
            $this->info('Repository files is exists');
            die();
        }

        $this->createInterfacesFolder($interfacesPath);
        $this->createRepositoriesFolder($repositoriesPath);

        $this->createInterfaceFile($interfacesName);

        $this->createRepositoryFile($repositoryName, $interfacesName);

        $controllerName= $name .'Controller';
        $this->createControllerFile($controllerName);

        $this->updateProviderFile($interfacesName, $repositoryName);
    }

}