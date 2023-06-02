<?php
namespace Unlimited\Repository\Interfaces;

interface StubGeneratorInterface
{
    public function generateFile($isResource = false);

    public function getStubVariables();

    public function getFileName();

    public function getFilePath();

    public function getNameSpace($semicolon= true, $withFile = null);

    public function getStubContents($stubPath , $stubVariables = []);
}