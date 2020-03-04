<?php

namespace Yard\GravityForms\BAGAddress;

class BAGLookup
{
    protected $zip = '';

    protected $homeNumber = '';

    protected $homeNumberAddition = '';

    protected $url = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free?q=postcode:{zip}%20and%20huisnummer:{homeNumber}%20and%20huisnummertoevoeging:{homeNumberAddition}%20and%20type:adres';

    final public function __construct()
    {
        $this->zip                   = $this->formatZip();
        $this->homeNumber            = $this->formatHomeNumber();
        $this->homeNumberAddition    = $this->formatHomeNumberAddition();
        $this->url                   = $this->parseURLvariables();
    }

    protected function handleResponse($response)
    {
        if (is_wp_error($response)) {
            return \wp_send_json_error(
                [
                    'message' => 'Er is een fout opgetreden',
                    'results' => $response
                ]
            );
        }
        $body        = wp_remote_retrieve_body($response);
        $data        = json_decode($body);
        $response    = $data->response;
        if (1 > $response->numFound) {
            return \wp_send_json_success(
                [
                    'message' => 'Geen resultaten gevonden',
                    'results' => []
                ]
            );
        }

        if (1 === $response->numFound) {
            $address = new BAGEntity($response->docs[0]);
            return wp_send_json_success([
                'message' => 'Succes!',
                'results' => [
                    'street'               => $address->straatnaam,
                    'houseNumber'          => $address->huisnummer,
                    'city'                 => $address->woonplaatsnaam,
                    'zip'                  => $address->postcode,
                    'state'                => $address->provincienaam,
                    'displayname'          => $address->weergavenaam
                ]
            ]);
        }

        if (1 < $response->numFound) {
            return \wp_send_json_success('Teveel resultaten gevonden. Probeer het adres specifieker te maken. Bijvoorbeeld met een huisnummer toevoeging.');
        }
    }

    public static function make(): self
    {
        return new static();
    }

    protected function parseURLvariables(): string
    {
        return str_replace(
            [
                '{zip}',
                '{homeNumber}',
                '{homeNumberAddition}'
            ],
            [
                $this->zip,
                $this->homeNumber,
                $this->homeNumberAddition
            ],
            $this->url
        );
    }

    public function execute()
    {
        return $this->handleResponse($this->call());
    }

    /**
     * Makes the call to remote.
     */
    protected function call()
    {
        return wp_remote_get($this->url);
    }

    /**
     * Format the zipcode.
     * Removes any spaces, escapes weird characters.
     *
     * @return string
     */
    protected function formatZip(): string
    {
        $zip = isset($_POST['zip']) ? esc_attr(trim($_POST['zip'])) : '';
        return preg_replace('/\s/', '', $zip);
    }

    /**
     * Format the homenumber.
     * Removes any spaces, escapes weird characters.
     *
     * @return string
     */
    protected function formatHomeNumber(): string
    {
        $homeNumber = isset($_POST['homeNumber']) ? esc_attr(trim($_POST['homeNumber'])) : '';
        return preg_replace('/\s/', '', $homeNumber);
    }

    /**
     * Format the homeNumberAddition.
     * Removes any spaces, escapes weird characters.
     *
     * @return string
     */
    protected function formatHomeNumberAddition(): string
    {
        $homeNumberAddition = isset($_POST['homeNumberAddition']) ? esc_attr(trim($_POST['homeNumberAddition'])) : '';
        return preg_replace('/\s/', '', $homeNumberAddition);
    }
}
