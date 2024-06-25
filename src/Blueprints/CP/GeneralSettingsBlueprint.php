<?php

namespace SteadfastCollective\ConvertKit\Blueprints\CP;

use Statamic\Facades\Form;
use Illuminate\Support\Arr;
use Statamic\Facades\Blueprint;
use Statamic\Facades\AssetContainer;

class GeneralSettingsBlueprint extends Blueprint
{
    /**
     * @inheritDoc
     */
    public static function requestBlueprint()
    {
        return Blueprint::make()->setContents([
            'sections' => [
                'api_token' => [
                    'display' => __('statamic-convertkit::general.api-token.title'),
                    'fields' => [
                        [
                            'handle' => 'api_token',
                            'field' => [
                                'type' => 'section',
                                'display' => __('statamic-convertkit::settings.api-token.title'),
                                'instructions' => __('statamic-convertkit::settings.api-token.instruction'),
                                'listable' => 'hidden',
                                'antlers' => false,
                                'icon' => 'section',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'always_save' => false
                            ],
                        ],
                        [
                            'handle' => 'forms',
                            'field' => [
                                'type' => 'form_mapping',
                                'display' => __('statamic-convertkit::settings.forms.title'),
                                'instructions' => __('statamic-convertkit::settings.forms.instruction'),
                                'listable' => 'hidden',
                                'antlers' => false,
                                'icon' => 'text',
                                'instructions_position' => 'above',
                                'visibility' => 'visible',
                                'always_save' => false,
                            ],
                        ],
                    ],
                ],
            ]
        ]);
    }
}
