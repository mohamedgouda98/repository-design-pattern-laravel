<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\File;

trait DirectoryHelper
{
    public function isExistsFiles($interfacesPath, $interfaceName)
    {
        $interfacesPath .= '/' .$interfaceName . '.php';
        return File::exists($interfacesPath);
    }

    public function getDirctoryPath($name)
    {
        $pathArray = explode('/', $name);
        unset($pathArray[array_key_last($pathArray)]);

        return implode('/', $pathArray);
    }

    public function getNameSpace($directory, $name, $semicolon= true, $withFile = null)
    {
        $pathArray = explode('/', $name);
        unset($pathArray[array_key_last($pathArray)]);

        $withFile = ($withFile) ? '\\' . $withFile : '';
        $semicolon = ($semicolon) ? ';' : '';

        return 'App\\Http\\' . $directory . implode('\\', $pathArray) . $withFile . $semicolon;
    }

    public function getFileName($name)
    {
        $pathArray = explode('/', $name);

        return end($pathArray);
    }

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


}