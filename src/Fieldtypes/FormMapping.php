<?php

namespace SteadfastCollective\ConvertKit\Fieldtypes;

use Statamic\Facades\Form;
use Statamic\Facades\Site;
use Statamic\Fields\Fieldtype;
use SteadfastCollective\ConvertKit\Facades\ConvertKitStorage;

class FormMapping extends Fieldtype
{
    /**
     * The blank/default value.
     *
     * @return array
     */
    public function defaultValue()
    {
        return null;
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param  mixed  $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        return $data;
    }

    /**
     * Process the data before it gets saved.
     *
     * @param  mixed  $data
     * @return array|mixed
     */
    public function process($data)
    {
        // strip out form fields data to not clog up the yaml
        if (! empty($data)) {
            foreach ($data['selectedForms'] as $key => $value) {
                unset($data['selectedForms'][$key]['fields']);
            }
        }

        return $data;
    }

    public function preload()
    {
        $forms = [];

        foreach (Form::all() as $form) {
            $forms[$form->handle] = [
                'label' => $form->title,
                'value' => $form->handle,
                'checked' => $this->checkFormStatus($form->handle),
                'fields' => $form->fields(),
            ];
        }

        return [
            'forms' => $forms,
        ];
    }

    public function checkFormStatus(string $handle): bool
    {
        $data = ConvertKitStorage::getYaml('general', Site::selected());
        $formSelected = false;

        if (isset($data['forms']['selectedForms'])) {
            foreach ($data['forms']['selectedForms'] as $form) {
                if ($form['value'] === $handle) {
                    $formSelected = true;
                }
            }
        }

        return $formSelected;
    }
}
