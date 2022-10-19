<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\Artisan;

trait CommandHelper
{
    public function createInterfaceFile($interfacesPath, $interfaceName, $interfaceNameSpace)
    {
        $sourceFile =  __DIR__ . "/../../stubs/RepositoryInterface.php.stub";

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryInterface', $interfaceName, $contents);
        $new_contents = str_replace('{Namespace}', $interfaceNameSpace, $new_contents);

        $this->files->put($interfacesPath . $interfaceName.'.php', $new_contents);

        $this->info('Repository Interface was successfully created.');
    }

    public function createRepositoryFile($repositoriesPath,$repositoryName, $InterfaceName, $repositoryNameSpace, $interfaceNameSpaceWithFileAndSemicolon)
    {
        $sourceFile =  __DIR__ . "/../../stubs/RepositoryClass.php.stub";

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryClass', $repositoryName, $contents);
        $new_contents = str_replace('RepositoryInterface', $InterfaceName, $new_contents);
        $new_contents = str_replace('{Namespace}', $repositoryNameSpace, $new_contents);
        $new_contents = str_replace('interfaceNamespaceWithFile', $interfaceNameSpaceWithFileAndSemicolon, $new_contents);


        $this->files->put($repositoriesPath . $repositoryName.'.php', $new_contents);

        $this->info('Repository Class was successfully created.');

    }

    public function createControllerFile($controllerName)
    {
        Artisan::call('make:controller ' . $controllerName);
        $this->info('Controller class was successfully created.');
    }

}