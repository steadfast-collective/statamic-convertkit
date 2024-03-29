<?php

namespace SteadfastCollective\ConvertKit;

use Illuminate\Support\Facades\Route;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;
use SteadfastCollective\ConvertKit\Http\Controllers\CP\ConvertKitController;
use SteadfastCollective\ConvertKit\Http\Controllers\CP\SettingsController;
use SteadfastCollective\ConvertKit\Http\Middleware\AddRefererToSessionStorage;
use SteadfastCollective\ConvertKit\Listeners\PushFormDataToConvertKit;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        Fieldtypes\FormMapping::class,
    ];

    protected $vite = [
        'input' => [
            'resources/js/addon.js',
        ],
        'publicDirectory' => 'dist',
    ];

    protected $listen = [
        FormSubmitted::class => [
            PushFormDataToConvertKit::class,
        ],
    ];

    protected $middlewareGroups = [
        'web' => [
            AddRefererToSessionStorage::class,
        ],
    ];

    public function bootAddon()
    {
        $this->registerCpRoutes(function () {
            Route::resource('convertkit', SettingsController::class)->only([
                'index', 'store',
            ]);

            Route::get('convertkit/get-forms', [ConvertKitController::class, 'getForms']);
            Route::get('convertkit/get-tags', [ConvertKitController::class, 'getTags']);
            Route::get('convertkit/get-custom-fields', [ConvertKitController::class, 'getCustomFields']);
        });

        Nav::extend(function ($nav) {
            $nav->tools('ConvertKit')
                ->route('convertkit.index')
                ->icon('<svg width="172" height="160" viewBox="0 0 172 160" fill="none"><path d="M82.72 126.316c29.77 0 52.78-22.622 52.78-50.526 0-26.143-21.617-42.106-35.935-42.106-19.945 0-35.93 14.084-38.198 34.988-.418 3.856-3.476 7.09-7.355 7.061-6.423-.046-15.746-.1-21.658-.08-2.555.008-4.669-2.065-4.543-4.618.89-18.123 6.914-35.07 18.402-48.087C58.976 8.488 77.561 0 99.565 0c36.969 0 71.869 33.786 71.869 75.79 0 46.508-38.312 84.21-87.927 84.21-35.384 0-71.021-23.258-83.464-55.775a.702.702 0 0 1-.03-.377c.165-.962.494-1.841.818-2.707.471-1.258.931-2.488.864-3.906l-.215-4.529a5.523 5.523 0 0 1 3.18-5.263l1.798-.842a6.982 6.982 0 0 0 3.912-5.075 6.993 6.993 0 0 1 6.887-5.736c5.282 0 9.875 3.515 11.59 8.512 8.307 24.212 21.511 42.014 53.873 42.014z" fill="#FB6970"/></svg>');
        });
    }
}
