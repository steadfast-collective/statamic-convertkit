<?php

namespace SteadfastCollective\ConvertKit;

use Statamic\Facades\Blueprint;

class SettingsBlueprint extends Blueprint
{
    /**
     * {@inheritDoc}
     */
    public static function getBlueprint()
    {
        return Blueprint::make()->setContents([
            'sections' => [
                'api_token' => [
                    'display' => __('convertkit::general.api-token.title'),
                    'fields' => [
                        [
                            'handle' => 'forms',
                            'field' => [
                                'type' => 'form_mapping',
                                'display' => __('convertkit::settings.forms.title'),
                                'instructions' => __('convertkit::settings.forms.instruction'),
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
            ],
        ]);
    }
}
