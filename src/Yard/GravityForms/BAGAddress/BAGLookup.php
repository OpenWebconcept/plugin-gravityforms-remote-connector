<?php

namespace Yard\GravityForms\BAGAddress;

use WP_Error;

class BAGLookup
{
    /**
     * Zip code string
     *
     * @var string
     */
    protected $zip = '';

    /**
     * Homenumber string
     *
     * @var string
     */
    protected $homeNumber = '';

    /**
     * Homenumber addition string
     *
     * @var string
     */
    protected $homeNumberAddition = '';

    /**
     * URL for BAG.
     *
     * @var string
     */
    private $url = 'https://geodata.nationaalgeoregister.nl/locatieserver/v3/free?q=postcode:{zip}%20and%20huisnummer:{homeNumber}%20and%20huisnummertoevoeging:{homeNumberAddition}%20and%20type:adres';

    final public function __construct()
    {
        $this->zip                   = $this->cleanUpInput('zip');
        $this->homeNumber            = $this->cleanUpInput('homeNumber');
        $this->homeNumberAddition    = $this->cleanUpInput('homeNumberAddition');
        $this->url                   = $this->parseURLvariables();
    }

    /**
     * Process the incoming response object.
     *
     * @param array|WP_Error $response HTTP response.
     *
     * @return string
     */
    protected function processResponse($response): string
    {
        if (is_wp_error($response)) {
            return wp_send_json_error(
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
            return wp_send_json_error(
                [
                    'message' => __('No results found', config('core.text_domain')),
                    'results' => []
                ]
            );
        }

        if (1 === $response->numFound) {
            $address = new BAGEntity($response->docs[0]);
            return wp_send_json_success([
                'message' => __('1 result found', config('core.text_domain')),
                'results' => [
                    'street'                 => $address->straatnaam,
                    'houseNumber'            => $address->huisnummer,
                    'city'                   => $address->woonplaatsnaam,
                    'zip'                    => $address->postcode,
                    'state'                  => $address->provincienaam,
                    'displayname'            => $address->weergavenaam
                ]
            ]);
        }

        return wp_send_json_error(
            [
                'message' => __('Found too many results. Try to make the address more specific. For example with a house number addition', config('core.text_domain')),
                'results' => []
            ]
        );
    }

    /**
     * Static constructor
     *
     * @return self
     */
    public static function make(): self
    {
        return new static();
    }

    /**
     * Parse the variables in the BAG url.
     *
     * @return string
     */
    private function parseURLvariables(): string
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

    /**
     * Actually execute the remote request.
     *
     * @return string
     */
    public function execute(): string
    {
        return $this->processResponse($this->call());
    }

    /**
     * Makes the call to remote.
     *
     * @return WP_Error|array The response or WP_Error on failure.
     */
    protected function call()
    {
        return wp_remote_get($this->url);
    }

    /**
     * Format the zipcode.
     * Removes any spaces, escapes weird characters.
     *
     * @param string $input
     *
     * @return string
     */
    protected function cleanUpInput($input = ''): string
    {
        $output = isset($_POST[$input]) ? esc_attr(trim($_POST[$input])) : '';
        $output = preg_replace('/\s/', '', $output);
        if ($output === null) {
            return '';
        }
        return $output;
    }
}
