<?php

namespace SteadfastCollective\ConvertKit\Facades;

use Illuminate\Support\Facades\Facade;
use SteadfastCollective\ConvertKit\Storage\GlobalsStorage;

class ConvertKitStorage extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return GlobalsStorage::class;
    }
}
