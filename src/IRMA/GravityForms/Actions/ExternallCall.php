<?php

namespace IRMA\WP\GravityForms\Actions;

use GuzzleHttp\Client;

class ExternallCall
{

    /**
     * Undocumented function
     *
     * @param [type] $entry
     * @param array $form
     * @return void
     */
    public function externalCall($entry, array $form)
    {
        // $dataForJSON = [
        //     'firstName'         => rgar($entry, '1'),
        //     'lastName'          => rgar($entry, '2'),
        //     'email'             => rgar($entry, '3'),
        //     'phoneNumber'       => rgar($entry, '4'),
        //     'address'           => rgar($entry, '5.1'),
        //     'city'              => rgar($entry, '5.3'),
        //     'postalCode'        => rgar($entry, '5.5'),
        //     'offerReference'    => rgar($entry, '8'),
        //     'question1'         => rgar($entry, '9'),
        //     'motivationText'    => rgar($entry, '10'),
        // ];

        $createdJSON = $this->createJSON();
        $test = $this->makeRequest($createdJSON);
        dd($test);

        // delete entry
        // \GFAPI::delete_entry($entry['id']);
    }

    /**
     * Create the JSON content
     *
     * @param array $dataForJSON
     * @return void
     */
    public function createJSON()
    {
        return  '{
                "bronorganisatie": "JOIN",
                "zaaktype": "https://ztcapi.zaaktypen.nl/f36b4854-5521-4923-af5d-3a119700c911",
                "verantwoordelijkeOrganisatie": "JOIN",
                "startdatum": "2019-09-23",
                "omschrijving": "Description",
                "toelichting": "Remarks",
                "publicatiedatum": "2019-10-23",
                "vertrouwelijkheidaanduiding": "openbaar",
                "betalingsindicatie": "nvt",
                "archiefstatus": "nog_te_archiveren",
                "kenmerken" : [
                        { "kenmerk" : "1234", "bron" : "formulierid website" }
                ]
            }';
    }

    public function makeRequest($JSON)
    {
        $authorization = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRfaWQiOiJZYXJkIn0.MsPgx9cTjz6xE7HIoqtYYvpK5W3KrKlvIMGhdtGQE8U';

        $client = new Client();

        $response = $client->request('POST', 'https://zgwapi.decos.com/api/v1/zaken', [
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
            ],
            'json' => $JSON,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
