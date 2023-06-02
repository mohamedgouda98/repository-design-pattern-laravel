<?php


namespace Unlimited\Repository\Classes;


use Illuminate\Filesystem\Filesystem;
use Unlimited\Repository\Console\Traits\DirectoryHelper;
use Unlimited\Repository\Interfaces\StubGeneratorInterface;

class InterfaceGenerator implements StubGeneratorInterface
{
    use DirectoryHelper;

    public $files;
    public $name;
    public $originalName;
    public $extraPath;
    public  $path = '/app/Http/Interfaces/';

    public function __construct($name, $originalName, $extraPath, Filesystem $filesystem)
    {
        $this->files = $filesystem;
        $this->name = $name;
        $this->originalName = $originalName;
        $this->extraPath = $extraPath;
    }

    public function generateFile($isResource = false)
    {
        $this->createFolder($this->getFilePath());

        if($isResource){
            $sourceFile = __DIR__ . "/../../stubs/RepositoryInterfaceResource.php.stub";
        }else{
            $sourceFile =  __DIR__ . "/../stubs/RepositoryInterface.php.stub";
        }

        $contents = $this->getStubContents($sourceFile,$this->getStubVariables());

        $this->files->put($this->getFilePath() . $this->getFileName().'.php', $contents);

        return true;
    }

    public function getStubVariables()
    {
        return ['RepositoryInterface' => $this->getFileName(),
            '{Namespace}' => $this->getNameSpace()];
    }

    public function getFileName()
    {
        return $this->name . 'InterFace';
    }

    public function getFilePath()
    {
        return base_path() . '/app/Http/Interfaces/'.  $this->extraPath;
    }

    public function getNameSpace($directory = "Interfaces\\", $semicolon= true, $withFile = null)
    {
        $pathArray = explode('/', $this->name);
        unset($pathArray[array_key_last($pathArray)]);

        $withFile = ($withFile) ? '\\' . $withFile : '';
        $semicolon = ($semicolon) ? ';' : '';

//        // if(the user enter a repository name with no prefix)
        if(count($pathArray) == 0){
            // delete the '\' at the end of $directory to avoid the additional '\'
            $directory =substr($directory, 0, -1);
        }

        return 'App\\Http\\'. $directory . implode('\\', $pathArray) . $withFile . $semicolon;
    }

    public function getStubContents($stubPath , $stubVariables = [])
    {
        $contents = file_get_contents($stubPath);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

}