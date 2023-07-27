<?php

namespace SteadfastCollective\ConvertKit\Storage;

use Illuminate\Support\Collection;
use Statamic\Facades\File;
use Statamic\Facades\Site;
use Statamic\Facades\YAML;
use Statamic\Sites\Site as SiteObject;

class GlobalsStorage implements Storage
{
    const prefix = 'convertkit';

    /**
     * Retrieve YAML data from storage
     *
     * @param  Site  $site
     * @return array|Collection
     */
    public static function getYaml(string $handle, SiteObject $site, bool $returnCollection = false)
    {
        $path = storage_path(implode('/', [
            'statamic/addons/convertkit',
            self::prefix.'_'."{$handle}.yaml",
        ]));

        $data = YAML::parse(File::get($path));

        $site_data = collect($data)->get($site->handle());

        if ($returnCollection) {
            return collect($site_data);
        }

        return collect($site_data)->toArray() ?: [];
    }

    /**
     * Retrieve YAML data from storage but back up using the default site
     *
     * @param  Site  $site
     * @return array
     */
    public function getYamlWithBackup(string $handle, SiteObject $site, bool $returnCollection = false)
    {
        $storage = self::getYaml($handle, $site, true);

        if (Site::hasMultiple() && $site !== Site::default()) {
            $default_storage = self::getYaml($handle, Site::default(), true);
            $storage = $default_storage->merge($storage);
        }

        if ($returnCollection) {
            return $storage;
        }

        return $storage->toArray() ?: [];
    }

    /**
     * Put YAML data into storage
     *
     * @param  Site  $site
     * @return void
     */
    public static function putYaml(string $handle, SiteObject $site, array $data)
    {
        $path = storage_path(implode('/', [
            'statamic/addons/convertkit',
            self::prefix.'_'."{$handle}.yaml",
        ]));

        $existing = collect(YAML::parse(File::get($path)));

        $combined_data = $existing->merge([
            "{$site->handle()}" => $data,
        ]);

        File::put($path, YAML::dump($combined_data->toArray()));
    }
}
