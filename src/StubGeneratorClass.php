<?php


namespace Unlimited\Repository;


use Illuminate\Filesystem\Filesystem;

class StubGeneratorClass
{
    public $stubPath;
    public $stubVariables;
    public $savePath;
    public $saveName;
    public $files;

    public function __construct($stubPath, $stubVariables, $savePath, $saveName, Filesystem $filesystem)
    {
        $this->stubPath = $stubPath;
        $this->stubVariables = $stubVariables;
        $this->savePath = $savePath;
        $this->saveName = $saveName;
        $this->files = $filesystem;
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

    public function putStubFile()
    {
        $this->files->put($this->savePath . $this->saveName.'.php', $this->getStubContents($this->stubPath, $this->stubVariables));
    }

}