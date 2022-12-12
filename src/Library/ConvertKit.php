<?php

namespace SteadfastCollective\ConvertKit\Library;

use Exception;
use Illuminate\Support\Facades\Http;

class ConvertKit
{
    private $key;
    private $secret;
    protected string $base;

    public function __construct()
    {
        $this->secret = config('convertkit.secret');
        $this->key = config('convertkit.key');
        $this->base = 'https://api.convertkit.com/v3/';
    }

    /**
     * Builds query for api requests
     * @param array $data additional data for request
     * @return array data
     * @throws
     */
    private function buildQuery(array $data = [])
    {
        if(!$this->key) {
            throw new Exception("No API key defined.");
        }

        $base_data = [
            'api_key' => $this->key
        ];

        return array_merge($data, $base_data);
    }

    public function getForms()
    {
        $response = Http::get("{$this->base}/forms", $this->buildQuery());

        if($response->failed()) {
            return [];
        }

        $data = $response->json();

        if(empty($data)) {
            return [];
        }

        $forms = [];

        foreach($data['forms'] as $form) {
            $forms[$form['id']] = [
                'id' => $form['id'],
                'name' => $form['name']
            ];
        }

        return $forms;
    }

    public function getTags()
    {
        $response = Http::get("{$this->base}/tags", $this->buildQuery());

        if($response->failed()) {
            return [];
        }

        $data = $response->json();

        if(empty($data)) {
            return [];
        }

        $forms = [];

        foreach($data['tags'] as $tag) {
            $tags[$tag['id']] = [
                'id' => $tag['id'],
                'name' => $tag['name']
            ];
        }

        return $tags;
    }

    public function getCustomFields()
    {
        $response = Http::get("{$this->base}/custom_fields", $this->buildQuery());

        if($response->failed()) {
            return [];
        }

        $data = $response->json();

        if(empty($data)) {
            return [];
        }

        $custom_fields = [];

        ray($data);

        foreach($data['custom_fields'] as $field) {
            $custom_fields[$field['id']] = [
                'id' => $field['id'],
                'key' => $field['key'],
                'name' => $field['label']
            ];
        }

        return $custom_fields;
    }


}
