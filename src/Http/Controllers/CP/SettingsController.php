<?php

namespace SteadfastCollective\ConvertKit\Http\Controllers\CP;

use Statamic\Facades\Site;
use Statamic\Http\Controllers\CP\CpController;
use SteadfastCollective\ConvertKit\Blueprints\CP\GeneralSettingsBlueprint;
use SteadfastCollective\ConvertKit\Facades\ConvertKitStorage;

class SettingsController extends CpController
{
    public function index()
    {
        $data = $this->getData();
        $blueprint = $this->getBlueprint();
        $fields = $blueprint->fields()->addValues($data)->preProcess();

        $key = config('convertkit.key');

        return view('convertkit::cp.settings.general', [
            'title' => 'Settings | ConvertKit',
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
            'key' => $key,
        ]);
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $original_data = $this->getData();

        $blueprint = $this->getBlueprint();

        $fields = $blueprint->fields()->addValues($request->all());
        $fields->validate();

        $data = $fields->process()->values()->toArray();
        $this->putData($data);

        // PipedriveSettingsUpdated::dispatch('general', $original_data, $data);
    }

    public function getBlueprint()
    {
        return GeneralSettingsBlueprint::requestBlueprint();
    }

    public function getData()
    {
        return ConvertKitStorage::getYaml('general', Site::selected());
    }

    public function putData($data)
    {
        return ConvertKitStorage::putYaml('general', Site::selected(), $data);
    }
}
