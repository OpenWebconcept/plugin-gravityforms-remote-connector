<?php

namespace IRMA\WP\GravityForms\Actions;

use Exception;
use GuzzleHttp\Client;
use IRMA\WP\Foundation\Plugin;

class ExternalCall
{
    /**
     * Prepare external call
     *
     * @param array $entry
     * @param array $form
     * @return array
     */
    public function externalCall($entry, array $form)
    {
        $formID = $form['fields'][0]['formId'];

        $formEntries = [
            'bsn'                   => rgar($entry, '21'),
            'sourceURL'             => rgar($entry, 'source_url'),
            'purchaseDateFirstDog'  => rgar($entry, '9'),
            'totalDogsAfter'        => rgar($entry, '20'),
            'hokRate'               => rgar($entry, '10'),
        ];

        $authorization = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRfaWQiOiJZYXJkIn0.MsPgx9cTjz6xE7HIoqtYYvpK5W3KrKlvIMGhdtGQE8U';

        try {
            $createdCaseJSON = $this->createCaseJSON($formEntries, $formID);
            $caseInstance = $this->createCase($createdCaseJSON, $authorization);

            // the case ID
            $caseURLwithID = $caseInstance['url'];
            $caseID = str_replace('-', '', $caseInstance['identificatie']);

            $createdCaseObjectJSON = $this->createCaseObjectJSON($caseURLwithID, $formEntries);
            $this->createCaseObject($createdCaseObjectJSON, $authorization);

            $this->createCaseProperties($caseURLwithID, $caseID, $formEntries, $authorization);
        } catch (Exception $e) {
            require(__DIR__ . '/../Views/FormSubmissionError.php');
            exit;
        }

        // return $casePropertyInstance;
    }

    /**
     * Create the JSON content
     *
     * @param array $dataForJSON
     * @param int $formID
     * @return string
     */
    public function createCaseJSON($dataForJSON, $formID): string
    {
        return  '{
            "bronorganisatie": "' . IRMA_WP_RSIN_BUREN . '",
            "zaaktype": "https://ztcapi.zaaktypen.nl/f36b4854-5521-4923-af5d-3a119700c911",
            "verantwoordelijkeOrganisatie": "' . IRMA_WP_RSIN_BUREN . '",
            "startdatum": "' . date('Y-m-d') . '",
            "omschrijving": "Aanmelding hondenbelasting", 
            "toelichting": "Remarks",
            "publicatiedatum": "' . date('Y-m-d') . '",
            "vertrouwelijkheidaanduiding": "openbaar",
            "betalingsindicatie": "nvt",
            "archiefstatus": "nog_te_archiveren",
            "kenmerken" : [
                    { "kenmerk" : "1234", "bron" : "formulierid website" }
            ]
        }';
    }

    /**
     * Create the JSON for a case object  
     *
     * @param string $caseURLwithID
     * @param array $formEntries
     * @return string
     */
    public function createCaseObjectJSON($caseURLwithID, $formEntries): string
    {
        $caseURL = preg_replace('/(?!.*\/).*/', '', $caseURLwithID);

        // $formEntries['bsn'] = '100148554';

        return '{
            "zaak": "' . $caseURLwithID . '",
            "object": "' . $caseURL . $formEntries['bsn'] . '",
            "objectType": "natuurlijkPersoon"
        }';
    }

    /**
     * Create the JSON content for a case property 
     *
     * @param string $caseURLwithID
     * @param string $propertyReference
     * @param string $caseValue
     * @return string
     */
    public function createCasePropertyJSON($caseURLwithID, $propertyReference, $caseValue): string
    {
        $caseURL = preg_replace('/(?!.*\/).*/', '', $caseURLwithID);

        return '{
            "zaak": "' . $caseURLwithID . '",
            "eigenschap": "' . $caseURL . $propertyReference . '", // needs url in front of it
            "waarde": "' . $caseValue . '"
        }';
    }

    /**
     * Execute the request and create a case
     *
     * @param string $JSON
     * @return array
     */
    public function createCase($JSON, $authorization): array
    {
        $client = new Client();

        // $response = $client->request('POST', 'https://zgwapi.decos.com/api/v1/zaken', [
        $response = $client->request('POST', 'https://buren-nlx-outway.yard.nl/Gemeente-Buren/DecosJoinV2/zaken', [
            'verify' => false,
            'body' => $JSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
            throw new Exception('Something went wrong.');
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Execute the request and create a case object
     *
     * @param string $createdCaseObjectJSON
     * @return bool
     */
    public function createCaseObject($createdCaseObjectJSON, $authorization): bool
    {
        $client = new Client();

        // $response = $client->request('POST', 'https://zgwapi.decos.com/api/v1/zaakobjecten', [
        $response = $client->request('POST', 'https://buren-nlx-outway.yard.nl/Gemeente-Buren/DecosJoinV2/zaakobjecten', [
            'verify' => false,
            'body' => $createdCaseObjectJSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
            throw new Exception('Something went wrong.');
        }

        return true;
    }

    /**
     * Execute the request and create a case object
     *
     * @param string $createdCaseObjectJSON
     * @return bool
     */
    public function createCaseProperty($createdCasePropertyJSON, $caseID, $authorization): bool
    {
        $client = new Client();

        // $response = $client->request('POST', 'https://zgwapi.decos.com/api/v1/zaken/' . $caseID . '/zaakeigenschappen', [
        $response = $client->request('POST', 'https://buren-nlx-outway.yard.nl/Gemeente-Buren/DecosJoinV2/zaken/' . $caseID . '/zaakeigenschappen', [
            'verify' => false,
            'body' => $createdCasePropertyJSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type' => 'application/json',
            ],
        ]);

        if ($response->getStatusCode() !== 201 && $response->getStatusCode() !== 200) {
            throw new Exception('Something went wrong.');
        }

        return true;
        // $result = json_decode($response->getBody()->getContents(), true);
    }

    public function createCaseProperties($caseURLwithID, $caseID, $formEntries, $authorization)
    {
        $pluginObject = (new Plugin(IRMA_WP__PLUGIN_URL));

        foreach ($formEntries as $key => $formEntry) {
            switch ($key) {
                case 'purchaseDateFirstDog':
                    $propertyReference = $pluginObject->getConfigCaseProperty('purchaseDateFirstDog');
                    $createdCasePropertyJSON = $this->createCasePropertyJSON($caseURLwithID, $propertyReference, $formEntry);
                    $this->createCaseProperty($createdCasePropertyJSON, $caseID, $authorization);
                    break;
                case 'hokRate':
                    $propertyReference = $pluginObject->getConfigCaseProperty('hokRate');
                    $createdCasePropertyJSON = $this->createCasePropertyJSON($caseURLwithID, $propertyReference, $formEntry);
                    $this->createCaseProperty($createdCasePropertyJSON, $caseID, $authorization);
                    break;
                case 'totalDogsAfter':
                    $propertyReference = $pluginObject->getConfigCaseProperty('totalDogsAfter');
                    $createdCasePropertyJSON = $this->createCasePropertyJSON($caseURLwithID, $propertyReference, $formEntry);
                    $this->createCaseProperty($createdCasePropertyJSON, $caseID, $authorization);
                    break;
            }
        }
    }
}
