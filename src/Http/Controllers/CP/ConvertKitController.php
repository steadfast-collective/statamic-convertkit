<?php

namespace SteadfastCollective\ConvertKit\Http\Controllers\CP;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Statamic\Http\Controllers\CP\CpController;
use SteadfastCollective\ConvertKit\Library\ConvertKit;

class ConvertKitController extends CpController
{
    public function getForms()
    {
        $ck = new ConvertKit();
        return $ck->getForms();
    }

    public function getTags()
    {
        $ck = new ConvertKit();
        return $ck->getTags();

    }

    public function getCustomFields()
    {
        $ck = new ConvertKit();
        return $ck->getCustomFields();
    }
}
