<?php

namespace Yard\OpenZaak\Client;

use Exception;

class OpenZaakClient
{
    /**
     * @var string|null
     */
    private $endpoint;

    /**
     * @var string|null
     */
    private $token;

    /**
     * @param string $endpoint
     * @param string $token
     */
    public function __construct(string $endpoint, string $token)
    {
        $this->endpoint = rtrim($endpoint, '/');
        $this->token    = rtrim($token);
    }

    /**
     * @param string $value
     *
     * @return OpenZaakClient
     */
    public function setToken($value): OpenZaakClient
    {
        $this->token = $value;

        return $this;
    }

    /**
     * @param array  $payload
     *
     * @return array
     */
    public function post(array $payload = []): array
    {
        $postArgs = [
            'headers' => [
                'Accept-Crs'	   => 'EPSG:4326',
                'Content-Crs'	  => 'EPSG:4326',
                'Content-Type'  => 'application/json',
            ],
            'body' => json_encode($payload),
        ];

        if (!empty($this->token)) {
            $postArgs = \array_merge_recursive($postArgs, [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->token,
                ]
            ]);
        }

        $response = wp_remote_post($this->endpoint .'/zaken', $postArgs);
		\dd($response);

        if (!in_array(wp_remote_retrieve_response_code($response), [200, 201])) {
            throw new Exception("Something went wrong: ". wp_remote_retrieve_response_message($response));
        }

        return json_decode(wp_remote_retrieve_body($response), true);
    }

    /**
     * @param string $endpoint
     *
     * @return array
     */
    private function get(string $endpoint): array
    {
        $response = wp_remote_get($this->endpoint.'/'.$endpoint);

        return json_decode(wp_remote_retrieve_body($response), true);
    }
}
