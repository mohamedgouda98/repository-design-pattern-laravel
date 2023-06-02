<?php


namespace Unlimited\Repository\Console\Traits;

use Illuminate\Support\Facades\File;

trait DirectoryHelper
{
    public function isExistsFiles($name)
    {
        $paths = [
            "Interface" => base_path() . '/app/Http/Interfaces/' .$name .'InterFace.php',
            "Repository" => base_path() . '/app/Http/Repositories/'.$name .'Repository.php'];
        foreach ($paths as $path)
        {
            if(File::exists($path)){
                return true;
            }
        }
        return false;
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
        
        // if(the user enter a repository name with no prefix)
        if(count($pathArray) == 0){
            // delete the '\' at the end of $directory to avoid the additional '\'
            $directory =substr($directory, 0, -1);
        }
 
        return 'App\\Http\\' . $directory . implode('\\', $pathArray) . $withFile . $semicolon;
    }

    public function getFileName($name)
    {
        $pathArray = explode('/', $name);

        return end($pathArray);
    }

    protected function createFolder($path)
    {
        if (File::exists($path)) {
            return;
        }

        if (! File::makeDirectory($path, 0755, true)) {
            throw new RuntimeException('Cannot create Interfaces folder');
        }
    }

}
