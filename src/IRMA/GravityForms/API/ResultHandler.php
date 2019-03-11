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

        // TEMPORARY!!

        $tmp_label_mapping = [
            'irma-demo.nijmegen.personalData.fullname' => 'Naam',
            'irma-demo.nijmegen.bsn.bsn' => 'BSN',
            // 'irma-demo.nijmegen.address.street' => 'Straat',
            // 'irma-demo.nijmegen.address.houseNumber' => 'Huisnummer',
            // 'irma-demo.nijmegen.address.city' => 'Stad',
        ];

        $result = array_map(function ($attribute) use ($tmp_label_mapping) {
            return [
                'label' => $tmp_label_mapping[$attribute->id],
                'attribute' => $attribute->id,
                'value' => $attribute->rawvalue
            ];
        }, $disclosed);

        return array_merge($result, [
            [
                'label' => 'Straat',
                'attribute' => 'irma-demo.nijmegen.address.street',
                'value' => 'Vossegatselaan'
            ],
            [
                'label' => 'Huisnummer',
                'attribute' => 'irma-demo.nijmegen.address.houseNumber',
                'value' => '32'
            ],
            [
                'label' => 'Stad',
                'attribute' => 'irma-demo.nijmegen.address.city',
                'value' => 'Utrecht'
            ],
        ]);
    }
}
