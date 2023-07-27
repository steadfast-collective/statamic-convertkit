<?php

namespace SteadfastCollective\ConvertKit\Http\Controllers\CP;

use Statamic\Facades\Site;
use Statamic\Http\Controllers\CP\CpController;
use SteadfastCollective\ConvertKit\Facades\ConvertKitStorage;
use SteadfastCollective\ConvertKit\SettingsBlueprint;

class SettingsController extends CpController
{
    public function index()
    {
        $data = $this->getData();
        $blueprint = SettingsBlueprint::getBlueprint();
        $fields = $blueprint->fields()->addValues($data)->preProcess();

        return view('convertkit::cp.settings.general', [
            'title' => 'Settings | ConvertKit',
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $blueprint = SettingsBlueprint::getBlueprint();

        $fields = $blueprint->fields()->addValues($request->all());
        $fields->validate();

        $data = $fields->process()->values()->toArray();
        $this->putData($data);
    }

    protected function getData()
    {
        return ConvertKitStorage::getYaml('general', Site::selected());
    }

    protected function putData($data)
    {
        return ConvertKitStorage::putYaml('general', Site::selected(), $data);
    }
}
