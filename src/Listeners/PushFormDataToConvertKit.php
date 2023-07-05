<?php

namespace SteadfastCollective\ConvertKit\Listeners;

use Statamic\Facades\Site;
use Statamic\Events\FormSubmitted;
use Illuminate\Support\Facades\Session;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use SteadfastCollective\ConvertKit\Library\ConvertKit;
use SteadfastCollective\ConvertKit\Facades\ConvertKitStorage;

class PushFormDataToConvertKit implements ShouldQueue
{

    use InteractsWithQueue;

    /**
     * @param Statamic\Events\FormSubmitted $event
     *
     * @return void
     */
    public function handle(FormSubmitted $event)
    {
        // Check for pipedrive token
        if(!config('convertkit.key')) {
            return;
        }

        // Check if pipedrive is enabled for this form
        $ck_settings = $this->isConvertKitEnabledForForm($event->submission->form()->handle());

        if(!$ck_settings) {
            return;
        }

        $data = $this->prepareData($event->submission->data(), $ck_settings);

        if(!$data) {
            return;
        }

        $ck = new ConvertKit();
        $ck->addSubscriberToForm($data['form_id'], $data['data']);
    }

    /**
     * Checks convertkit settings if integration is enabled
     * @param string $handle Form Handle
     * @return array|bool convertkit settings, or false
     */
    public function isConvertKitEnabledForForm(string $handle)
    {
        $settings = ConvertKitStorage::getYaml('general', Site::selected());

        foreach($settings['forms']['selectedForms'] as $form) {
            if($form['value'] === $handle) {
                return $form;
            }
        }

        return false;
    }

    public function prepareData($form_data, $settings)
    {
        $form_id = null;
        $data = [];

        foreach($settings['mappings'] as $field) {
            if(!$field['form_field'] && $field['convertkit_name'] === 'form' || !$field['form_field'] && $field['convertkit_name'] === 'email') {
                return false;
            }

            if($field['convertkit_name'] === 'form') {
                $form_id = $field['form_field'];
            } else {
                if($field['convertkit_name'] === 'custom_field') {
                    if(isset($field['custom_key'])) {
                        if($field['form_field'] === 'custom_value') {
                            $val = $field['custom_value'];
                        } elseif($field['form_field'] === 'HTTP_REFERER'){
                            $val = Session::get('referer') ?? '';
                        } else {
                            $val = $form_data[$field['form_field']];
                        }

                        if($val) {
                            $data['fields'][$field['custom_key']] = $val;
                        }
                    }
                } else {
                    if($field['form_field'] === 'custom_value') {
                        $data[$field['convertkit_name']] = $field['custom_value'];
                    } else {
                        $data[$field['convertkit_name']] = $form_data[$field['form_field']];
                    }

                    if($field['convertkit_name'] === 'tags') {
                        if(!empty($field['selected_tags'])) {
                            $data[$field['convertkit_name']] = $field['selected_tags'];
                        }
                    }
                }
            }
        }

        return [
            'form_id' => $form_id,
            'data' => $data
        ];

    }
}
