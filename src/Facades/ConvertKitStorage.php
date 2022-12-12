<?php

namespace SteadfastCollective\ConvertKit\Facades;

use SteadfastCollective\ConvertKit\Storage\GlobalsStorage;
use Illuminate\Support\Facades\Facade;

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
