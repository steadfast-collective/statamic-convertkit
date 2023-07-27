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
    public static function getYaml(string $handle, SiteObject $site, bool $returnCollection = false): array
    {
        $path = static::getPath($handle);

        $data = YAML::parse(File::get($path));

        $siteData = collect($data)->get($site->handle());

        if ($returnCollection) {
            return collect($siteData);
        }

        return collect($siteData)->toArray() ?: [];
    }

    /**
     * Retrieve YAML data from storage but back up using the default site
     *
     * @param  Site  $site
     */
    public function getYamlWithBackup(string $handle, SiteObject $site, bool $returnCollection = false): array
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
     */
    public static function putYaml(string $handle, SiteObject $site, array $data): void
    {
        $path = static::getPath($handle);

        $existingData = collect(YAML::parse(File::get($path)));

        File::put(
            $path,
            YAML::dump($existingData->merge([$site->handle() => $data])->toArray())
        );
    }

    protected static function getPath(string $handle): string
    {
        return storage_path(implode('/', [
            'statamic/addons/convertkit',
            self::prefix.'_'."{$handle}.yaml",
        ]));
    }
}
