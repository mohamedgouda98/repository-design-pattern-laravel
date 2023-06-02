<?php


namespace Unlimited\Repository\Classes;


use Unlimited\Repository\Interfaces\StubGeneratorInterface;

class GeneratorContext
{
    public $generatorInterface;
    public function __construct(StubGeneratorInterface $generatorInterface)
    {
        $this->generatorInterface = $generatorInterface;
    }

    public function setStrategy(StubGeneratorInterface $generatorInterface)
    {
        return $this->generatorInterface = $generatorInterface;
    }

    public function generateFile()
    {
        return $this->generatorInterface->generateFile();
    }

}