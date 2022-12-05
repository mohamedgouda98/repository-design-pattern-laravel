<?php


namespace Unlimited\Repository\Console;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Unlimited\Repository\Console\Traits\CommandHelper;
use Unlimited\Repository\Console\Traits\DirectoryHelper;
use Unlimited\Repository\Console\Traits\ProviderHelper;

class CreateRepository extends Command
{
    use CommandHelper;
    use ProviderHelper;
    use DirectoryHelper;

    protected $signature="repository:create {name}";

    protected $description="Create New Repository";

    public $files;

    public $isExists = false;


    public function handle(Filesystem $filesystem)
    {
        $this->files = $filesystem;

        $originalName = $this->argument('name');
        $name = $this->getFileName($originalName);
        $extraPath = $this->getDirctoryPath($originalName) . '/';

        $repositoriesPath = base_path() . '/app/http/Repositories/' . $extraPath;
        $interfacesPath = base_path() . '/app/http/Interfaces/' . $extraPath;

        $interfacesName= $name . 'Interface';
        $repositoryName= $name . 'Repository';

        $interfaceNameSpace = $this->getNameSpace('Interfaces\\', $originalName);
        $repositoryNameSpace = $this->getNameSpace('Repositories\\', $originalName);

        $interfaceNameSpaceWithFileAndSemicolon = $this->getNameSpace('Interfaces\\', $originalName, true, $interfacesName);

        $this->isExists = $this->isExistsFiles($interfacesPath, $interfacesName);
        if($this->isExists == true)
        {
            $this->info('Repository files is exists');
            die();
        }

        $this->createInterfacesFolder($interfacesPath);
        $this->createRepositoriesFolder($repositoriesPath);

        $this->createInterfaceFile($interfacesPath, $interfacesName, $interfaceNameSpace);

        $this->createRepositoryFile($repositoriesPath, $repositoryName, $interfacesName, $repositoryNameSpace, $interfaceNameSpaceWithFileAndSemicolon);

        $controllerName= $originalName .'Controller';
        $this->createControllerFile($controllerName);

    }

}