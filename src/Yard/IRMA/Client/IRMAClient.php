<?php

namespace Yard\IRMA\Client;

use UnexpectedValueException;
use Yard\IRMA\Attribute;
use Yard\IRMA\AttributeCollection;

class IRMAClient
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
     */
    public function __construct($endpoint, $token)
    {
        if (empty($endpoint)) {
            throw new UnexpectedValueException('An endpoint should not be empty.');
        }
        $this->endpoint = rtrim($endpoint, '/');
        $this->token    = rtrim($token);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function getSession(array $attributes): array
    {
        return $this->post('session', $this->token, [
            'type'    => 'disclosing',
            'content' => $attributes,
        ]);
    }

    /**
     * @return AttributeCollection
     */
    public function getResult(): AttributeCollection
    {
        $collection = AttributeCollection::make();

        foreach ($this->get("session/$this->token/result")['disclosed'] ?? [] as $attr) {
            $collection->add(new Attribute($attr['id'], $attr['rawvalue'], $attr['value'], $attr['status']));
        }

        return $collection;
    }

    /**
     * @param string $value
     *
     * @return IRMAClient
     */
    public function setToken($value): IRMAClient
    {
        $this->token = $value;

        return $this;
    }

    /**
     * @param string $endpoint
     * @param string $token
     * @param array  $payload
     *
     * @return array
     */
    private function post(string $endpoint, string $token, array $payload = []): array
    {
        $postArgs = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => json_encode($payload),
        ];

        if (!empty($token)) {
            $postArgs = [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type'  => 'application/json',
                ],
                'body' => json_encode($payload),
            ];
        }

        $response = wp_remote_post($this->endpoint.'/'.$endpoint, $postArgs);

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
