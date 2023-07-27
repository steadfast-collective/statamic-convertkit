<?php

namespace SteadfastCollective\ConvertKit\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use Statamic\Events\FormSubmitted;
use Statamic\Facades\Site;
use SteadfastCollective\ConvertKit\Facades\ConvertKitStorage;
use SteadfastCollective\ConvertKit\Library\ConvertKit;

class PushFormDataToConvertKit implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @param  Statamic\Events\FormSubmitted  $event
     * @return void
     */
    public function handle(FormSubmitted $event)
    {
        // Check for pipedrive token
        if (! config('convertkit.key')) {
            return;
        }

        // Check if ConvertKit is enabled for this form
        $convertKitSettings = $this->isConvertKitEnabledForForm($event->submission->form()->handle());

        if (! $convertKitSettings) {
            return;
        }

        $data = $this->prepareData($event->submission->data(), $convertKitSettings);

        if (! $data) {
            return;
        }

        $convertKit = new ConvertKit();
        $convertKit->addSubscriberToForm($data['form_id'], $data['data']);
    }

    /**
     * Checks convertkit settings if integration is enabled
     *
     * @param  string  $handle Form Handle
     * @return array|bool convertkit settings, or false
     */
    public function isConvertKitEnabledForForm(string $handle)
    {
        $settings = ConvertKitStorage::getYaml('general', Site::selected());

        foreach ($settings['forms']['selectedForms'] as $form) {
            if ($form['value'] === $handle) {
                return $form;
            }
        }

        return false;
    }

    public function prepareData($form_data, $settings)
    {
        $formId = null;
        $data = [];

        foreach ($settings['mappings'] as $field) {
            if (! $field['form_field'] && $field['convertkit_name'] === 'form' || ! $field['form_field'] && $field['convertkit_name'] === 'email') {
                return false;
            }

            if ($field['convertkit_name'] === 'form') {
                $formId = $field['form_field'];
            } else {
                if ($field['convertkit_name'] === 'custom_field') {
                    if (isset($field['custom_key'])) {
                        if ($field['form_field'] === 'custom_value') {
                            $value = $field['custom_value'];
                        } elseif ($field['form_field'] === 'HTTP_REFERER') {
                            $value = Session::get('referer') ?? '';
                        } else {
                            $value = $form_data[$field['form_field']];
                        }

                        if ($value) {
                            $data['fields'][$field['custom_key']] = $value;
                        }
                    }
                } else {
                    if ($field['form_field'] === 'custom_value') {
                        $data[$field['convertkit_name']] = $field['custom_value'];
                    } else {
                        $data[$field['convertkit_name']] = $form_data[$field['form_field']];
                    }

                    if ($field['convertkit_name'] === 'tags') {
                        if (! empty($field['selected_tags'])) {
                            $data[$field['convertkit_name']] = $field['selected_tags'];
                        }
                    }
                }
            }
        }

        return [
            'form_id' => $formId,
            'data' => $data,
        ];

    }
}
