<?php

namespace IRMA\WP\GravityForms\API;

class ResultHandler
{
    public function handle()
    {
        $token = $_POST['token'];
        $endpoint = "https://metrics.privacybydesign.foundation/irmaserver/session/$token/result";

        $request = wp_remote_post($endpoint, [
                'method' => 'GET',
            ]);

        $response = json_decode(wp_remote_retrieve_body($request));
        $disclosed = isset($response->disclosed) ? $response->disclosed : [];

        return array_map(function ($attribute) {
            return [
                'label' => 'Over 18',
                'attribute' => $attribute->id,
                'value' => $attribute->rawvalue
            ];
        }, $disclosed);
    }
}
