<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\Artisan;

trait CommandHelper
{
    public function createInterfaceFile($interfacesPath, $interfaceName, $interfaceNameSpace, $isResource = false)
    {
        if($isResource){
            $sourceFile = __DIR__ . "/../../stubs/RepositoryInterfaceResource.php.stub";
        }else{
            $sourceFile =  __DIR__ . "/../../stubs/RepositoryInterface.php.stub";
        }

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryInterface', $interfaceName, $contents);
        $new_contents = str_replace('{Namespace}', $interfaceNameSpace, $new_contents);

        $this->files->put($interfacesPath . $interfaceName.'.php', $new_contents);

        $this->info('Repository Interface was successfully created.');
    }

    public function createRepositoryFile($repositoriesPath,$repositoryName, $InterfaceName, $repositoryNameSpace, $interfaceNameSpaceWithFileAndSemicolon, $isResource=false)
    {
        if($isResource){
            $sourceFile = __DIR__ . "/../../stubs/RepositoryClassResource.php.stub";
        }else{
            $sourceFile =  __DIR__ . "/../../stubs/RepositoryClass.php.stub";
        }

        $contents = file_get_contents($sourceFile);
        $new_contents = str_replace('RepositoryClass', $repositoryName, $contents);
        $new_contents = str_replace('RepositoryInterface', $InterfaceName, $new_contents);
        $new_contents = str_replace('{Namespace}', $repositoryNameSpace, $new_contents);
        $new_contents = str_replace('interfaceNamespaceWithFile', $interfaceNameSpaceWithFileAndSemicolon, $new_contents);


        $this->files->put($repositoriesPath . $repositoryName.'.php', $new_contents);

        $this->info('Repository Class was successfully created.');

    }

    public function createControllerFile($controllerName, $resource = false)
    {
        if($resource)
        {
            Artisan::call('make:controller ' . $controllerName . ' --resource');

        }else{
            Artisan::call('make:controller ' . $controllerName);
        }
        $this->info('Controller class was successfully created.');
    }

}