<?php

return [
    /*
     * Service Providers.
     */
    'providers' => [
        Yard\IRMA\IRMAServiceProvider::class,
        Yard\OpenZaak\OpenZaakServiceProvider::class,
        Yard\Settings\SettingsServiceProvider::class,
    ],

    'text_domain' => 'irma_wp',
];
