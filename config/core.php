<?php

return [
    /*
     * Service Providers.
     */
    'providers' => [
        Yard\Connector\ConnectorManagerServiceProvider::class,
        Yard\GravityForms\GravityFormsServiceProvider::class,
        Yard\OpenZaak\OpenZaakServiceProvider::class,
        Yard\IRMA\IRMAServiceProvider::class
    ],

    'connectors' => [
        Yard\OpenZaak\OpenZaakConnector::class,
    ],

    'text_domain' => 'gravityforms-remote-connector',
];
