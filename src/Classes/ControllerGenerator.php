<?php


namespace Unlimited\Repository\Classes;


use Illuminate\Filesystem\Filesystem;
use Unlimited\Repository\Console\Traits\DirectoryHelper;
use Unlimited\Repository\Interfaces\StubGeneratorInterface;

class ControllerGenerator implements StubGeneratorInterface
{
    use DirectoryHelper;

    public $files;
    public $name;
    public $originalName;
    public $path = '/app/Http/Controller/';
    public $extraPath;

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
            $sourceFile = __DIR__ . "/../../stubs/Repository.resource.stub";
        }else{
            $sourceFile = __DIR__ . "/../../stubs/controller.plain.stub";
        }

        $contents = $this->getStubContents($sourceFile,$this->getStubVariables());

        $this->files->put($this->getFilePath() . $this->getFileName().'.php', $contents);

        return true;
    }

    public function getStubVariables()
    {
        return [
            '{{ class }}' => $this->name ."Controller",
            '{{ namespace }}' => $this->getNameSpace(),
            '{{ InterFaceName }}' => $this->name . "Interface",
            '{{ useInterface }}' => $this->getNameSpace("Interfaces\\", true, $this->name . "Interface")
        ];
    }

    public function getFileName()
    {
        return $this->name . 'Controller';
    }

    public function getFilePath()
    {
        return base_path() . '/app/Http/Controllers/' . $this->getDirectoryFoldersNames($this->originalName);
    }

    public function getNameSpace($directory= "Controllers\\", $semicolon= true, $withFile = null)
    {
        $pathArray = explode('/', $this->name);
        unset($pathArray[array_key_last($pathArray)]);

        $withFile = ($withFile) ? '\\' . $withFile : '';
        $semicolon = ($semicolon) ? ';' : '';

        // if(the user enter a controller name with no prefix)
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