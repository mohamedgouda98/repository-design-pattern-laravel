<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\Artisan;

trait CommandHelper
{
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

    public function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

}