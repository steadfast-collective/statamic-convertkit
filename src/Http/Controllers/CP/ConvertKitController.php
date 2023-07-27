<?php

namespace SteadfastCollective\ConvertKit\Http\Controllers\CP;

use Statamic\Http\Controllers\CP\CpController;
use SteadfastCollective\ConvertKit\Library\ConvertKit;

class ConvertKitController extends CpController
{
    public function getForms()
    {
        $convertKit = new ConvertKit();

        return $convertKit->getForms();
    }

    public function getTags()
    {
        $convertKit = new ConvertKit();

        return $convertKit->getTags();

    }

    public function getCustomFields()
    {
        $convertKit = new ConvertKit();

        return $convertKit->getCustomFields();
    }
}
