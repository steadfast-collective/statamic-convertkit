<?php

namespace SteadfastCollective\ConvertKit\Library;

use Exception;
use Illuminate\Support\Facades\Http;

class ConvertKit
{
    private $key;

    protected string $base;

    public function __construct()
    {
        $this->key = config('convertkit.key');
        $this->base = 'https://api.convertkit.com/v3';
    }

    /**
     * Builds query for api requests
     *
     * @param  array  $data additional data for request
     * @return array data
     *
     * @throws Exception if no API key is present
     */
    private function buildQuery(array $data = []): array
    {
        if (! $this->key) {
            throw new Exception('No API key defined.');
        }

        $baseData = [
            'api_key' => $this->key,
        ];

        return array_merge($data, $baseData);
    }

    /**
     * Gets forms from ConvertKit
     *
     * @see https://developers.convertkit.com/#list-forms
     */
    public function getForms(): array
    {
        $response = Http::get("{$this->base}/forms", $this->buildQuery());

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        if (empty($data)) {
            return [];
        }

        $forms = [];

        foreach ($data['forms'] as $form) {
            $forms[$form['id']] = [
                'id' => $form['id'],
                'name' => $form['name'],
            ];
        }

        return $forms;
    }

    /**
     * Gets tags from ConvertKit
     *
     * @see https://developers.convertkit.com/#list-tags
     */
    public function getTags(): array
    {
        $response = Http::get("{$this->base}/tags", $this->buildQuery());

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        if (empty($data)) {
            return [];
        }

        $tags = [];

        foreach ($data['tags'] as $tag) {
            $tags[$tag['id']] = [
                'id' => $tag['id'],
                'name' => $tag['name'],
            ];
        }

        return $tags;
    }

    /**
     * Gets custom fields from ConvertKit
     *
     * @see https://developers.convertkit.com/#list-fields
     */
    public function getCustomFields(): array
    {
        $response = Http::get("{$this->base}/custom_fields", $this->buildQuery());

        if ($response->failed()) {
            return [];
        }

        $data = $response->json();

        if (empty($data)) {
            return [];
        }

        $custom_fields = [];

        foreach ($data['custom_fields'] as $field) {
            $custom_fields[$field['id']] = [
                'id' => $field['id'],
                'key' => $field['key'],
                'name' => $field['label'],
            ];
        }

        return $custom_fields;
    }

    /**
     * Send form data to ConvertKit
     *
     * @param  int  $form form ID
     * @param  array  $subscriber_data data to be passed to convert kit
     * @return bool|array false on fail, array of subscriber data on success
     */
    public function addSubscriberToForm(int $form, array $subscriber_data): array|bool
    {
        if (! $this->key) {
            return false;
        }

        // Add api key to post data
        $subscriber_data['api_key'] = $this->key;

        $response = Http::post("{$this->base}/forms/{$form}/subscribe", $subscriber_data);

        if ($response->failed()) {
            return false;
        }

        return $response->json();

    }
}
