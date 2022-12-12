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
        $value = Cache::remember('convertkit_forms', now()->addHours(3), function() {
            $ck = new ConvertKit();
            return $ck->getForms();
        });

        return $value;
    }

    public function getTags()
    {
        $value = Cache::remember('convertkit_tags', now()->addHours(3), function() {
            $ck = new ConvertKit();
            return $ck->getTags();
        });

        return $value;
    }

    public function getCustomFields()
    {
        $value = Cache::remember('convertkit_custom_fields', now()->addHours(3), function() {
            $ck = new ConvertKit();
            return $ck->getCustomFields();
        });

        return $value;
    }
}
