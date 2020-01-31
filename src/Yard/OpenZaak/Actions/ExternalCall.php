<?php

namespace Yard\OpenZaak\Actions;

use Exception;
use GuzzleHttp\Client;
use Yard\Foundation\SettingsManager;

class ExternalCall
{
    /**
     * HTTP Client
     *
     * @var Client	$client
     * @var SettingsManager $settingsManager
     */
    protected $client;

    public function __construct(Client $client, SettingsManager $settingsManager)
    {
        $this->client          = $client;
        $this->settingsManager = $settingsManager;
    }

    /**
     * Prepare external call.
     *
     * @param array $entry
     * @param array $form
     *
     * @return array
     */
    public function handle(array $entry, array $form)
    {
        $formID      = $form['fields'][0]['formId'];
        $formEntries = [];

        foreach ($form['fields'] as $field) {
            array_push($formEntries, ['casePropertyValue' => trim(rgar($entry, (string) $field['id'])), 'casePropertyName' => trim($field['casePropertyName'])]);
        }

        $authorization = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnRfaWQiOiJZYXJkIn0.MsPgx9cTjz6xE7HIoqtYYvpK5W3KrKlvIMGhdtGQE8U';

        try {
            $createdCaseJSON = $this->createCaseJSON($formEntries, $formID);
            $caseInstance    = $this->createCase($createdCaseJSON, $authorization);

            // the case ID
            $caseURLwithID = $caseInstance['url'];
            $caseID        = str_replace('-', '', $caseInstance['identificatie']);

            $createdCaseObjectJSON = $this->createCaseObjectJSON($caseURLwithID, $formEntries);

            $this->createCaseObject($createdCaseObjectJSON, $authorization);
            $this->createCaseProperties($caseURLwithID, $caseID, $formEntries, $authorization);
        } catch (Exception $e) {
            require __DIR__ . '/../views/form-submission-error.php';
            exit;
        }
    }

    /**
     * Create the JSON content.
     *
     * @param array $dataForJSON
     * @param int   $formID
     *
     * @return string
     */
    public function createCaseJSON($dataForJSON, $formID): string
    {
        return  '{
            "bronorganisatie": "' . $this->settingsManager->getRISN() . '",
            "zaaktype": "https://ztcapi.zaaktypen.nl/f36b4854-5521-4923-af5d-3a119700c911",
            "verantwoordelijkeOrganisatie": "' . $this->settingsManager->getRISN() . '",
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
     * Create the JSON for a case object.
     *
     * @param string $caseURLwithID
     * @param array  $formEntries
     *
     * @return string
     */
    public function createCaseObjectJSON($caseURLwithID, $formEntries): string
    {
        $caseURL = preg_replace('/(?!.*\/).*/', '', $caseURLwithID);

        $BSN = '';

        foreach ($formEntries as $formEntry) {
            if ('BSN' === $formEntry['casePropertyName']) {
                $BSN = $formEntry['casePropertyValue'] ?? false;

                continue;
            }
        }

        if (!$BSN) {
            throw new Exception('The BSN is not present');
        }

        return '{
            "zaak": "' . $caseURLwithID . '",
            "object": "' . $caseURL . $BSN . '",
            "objectType": "natuurlijkPersoon"
        }';
    }

    /**
     * Create the JSON content for a case property.
     *
     * @param string $caseURLwithID
     * @param string $propertyReference
     * @param string $caseValue
     *
     * @return string
     */
    public function createCasePropertyJSON($caseURLwithID, $propertyReference, $caseValue): string
    {
        $caseURL = preg_replace('/(?!.*\/).*/', '', $caseURLwithID);

        return '{
            "zaak": "' . $caseURLwithID . '",
            "eigenschap": "' . $caseURL . $propertyReference . '",
            "waarde": "' . $caseValue . '"
        }';
    }

    /**
     * Execute the request and create a case.
     *
     * @param string $JSON
     *
     * @return array
     */
    public function createCase($JSON, $authorization): array
    {
        $response = $this->client->request('POST', $this->settingsManager->createCaseURL(), [
            'verify'  => false,
            'body'    => $JSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type'  => 'application/json',
            ],
        ]);

        if (201 !== $response->getStatusCode() && 200 !== $response->getStatusCode()) {
            throw new Exception('Something went wrong.');
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * Execute the request and create a case object.
     *
     * @param string $createdCaseObjectJSON
     *
     * @return bool
     */
    public function createCaseObject($createdCaseObjectJSON, $authorization): bool
    {
        $response = $this->client->request('POST', $this->settingsManager->createCaseObjectURL(), [
            'verify'  => false,
            'body'    => $createdCaseObjectJSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type'  => 'application/json',
            ],
        ]);

        if (201 !== $response->getStatusCode() && 200 !== $response->getStatusCode()) {
            throw new Exception('Something went wrong.');
        }

        return true;
    }

    /**
     * Execute the request and create a case object.
     *
     * @param string $createdCaseObjectJSON
     *
     * @return bool
     */
    public function createCaseProperty($createdCasePropertyJSON, $caseID, $authorization): bool
    {
        $createCasePropertyURL = str_replace('caseID', $caseID, $this->settingsManager->createCasePropertyURL());

        $response = $this->client->request('POST', $createCasePropertyURL, [
            'verify'  => false,
            'body'    => $createdCasePropertyJSON,
            'headers' => [
                'Authorization' => 'Bearer ' . $authorization,
                'Content-Type'  => 'application/json',
            ],
        ]);

        if (201 !== $response->getStatusCode() && 200 !== $response->getStatusCode()) {
            throw new Exception('Something went wrong.');
        }

        return true;
    }

    /**
     * Create JSON object and create a case from the JSON object.
     *
     * @param string $caseURLwithID
     * @param string $caseID
     * @param array  $formEntries
     * @param string $authorization
     */
    public function createCaseProperties($caseURLwithID, $caseID, $formEntries, $authorization)
    {
        // create caseProperties separately for every form field
        foreach ($formEntries as $formEntry) {
            if (!empty($formEntry['casePropertyName']) && 'BSN' !== $formEntry['casePropertyName']) {
                $createdCasePropertyJSON = $this->createCasePropertyJSON($caseURLwithID, $formEntry['casePropertyName'], $formEntry['casePropertyValue']);
                $this->createCaseProperty($createdCasePropertyJSON, $caseID, $authorization);
            }
        }
    }
}
