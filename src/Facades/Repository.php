<?php


namespace Unlimited\Repository\Facades;


use Illuminate\Support\Facades\Facade;

class Repository extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'repository';
    }

}