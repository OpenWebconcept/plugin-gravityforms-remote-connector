<?php

namespace Yard\OpenZaak\Actions;

use Exception;
use Yard\Foundation\SettingsManager;
use Yard\OpenZaak\Client\OpenZaakClient;

class ExternalCall
{
    /**
     * HTTP Client
     *
     * @var OpenZaakClient	$client
     * @var SettingsManager $settingsManager
     */
    protected $client;

    /**
     * SettingsManager.
     *
     * @var SettingsManager
     */
    protected $settingsManager;

    public function __construct(OpenZaakClient $client, SettingsManager $settingsManager)
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
        try {
            $createdCaseJSON = $this->createCaseJSON($formEntries);
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
    public function createCase($json): array
    {
        $response = $this->client->post($json);
        dd($response);

        if (201 !== $response->getStatusCode() && 200 !== $response->getStatusCode()) {
            throw new Exception('Something went wrong.');
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
