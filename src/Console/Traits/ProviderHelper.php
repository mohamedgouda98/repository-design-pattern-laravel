<?php

namespace Unlimited\Repository\Console\Traits;

trait ProviderHelper
{
    public $originFilesName= [];

    public function getFilesListInDirectory($path, $endLength, $extraPath=null)
    {

        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file)
        {
            $extension = mb_substr($file, -4);
            if($extension == '.php')
            {
                $this-> originFilesName[] = ($extraPath)? $extraPath .  '\\' . substr($file, 0,$endLength) : substr($file, 0,$endLength);
            }else{
                $newPath = $path . '/' . $file;
                $this->getFilesListInDirectory($newPath, $endLength, $file);
            }
        }

        return $this->originFilesName;
    }

    public function bindFiles($originInterfacesFileName, $originRepositoriesFileName)
    {
        $definedFiles = array_intersect($originInterfacesFileName, $originRepositoriesFileName);

        foreach ($definedFiles as $definedFile)
        {
            $this->app->bind(
                'App\Http\Interfaces\\' . $definedFile . 'Interface',
                'App\Http\Repositories\\' . $definedFile .'Repository'
            );
        }

    }
}