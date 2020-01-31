<?php

namespace Yard\IRMA\GravityForms\API;

use GFAPI;
use WP_REST_Request;
use WP_REST_Response;
use Yard\IRMA\Client\IRMAClient;
use Yard\IRMA\Client\SessionAttributeCollection;

class Session
{
    /**
     * @var IRMAClient
     */
    private $client;

    public function __construct(IRMAClient $client)
    {
        $this->client = $client;
    }

    /**
     * Create an IRMA session for a specific form.
     *
     * @param WP_REST_Request $request
     */
    public function handle(WP_REST_Request $request)
    {
        $formId = $request->get_param('id');
        $form   = GFAPI::get_form($request->get_param('id'));

        if (!$form) {
            return new WP_REST_Response([
                'error' => 'Could not find form.',
            ], 400);
        }

        $attributes = [];

        foreach ($form['fields'] as $field) {
            if ('IRMA-attribute' != $field['type'] && 'IRMA-header' != $field['type']) {
                continue;
            }

            switch ($field['type']) {
                case 'IRMA-attribute':
                    $attributes[] = SessionAttributeCollection::make()
                        ->setLabel($field['label'])
                        ->add($field['irmaAttribute']);
                    break;
                case 'IRMA-header':
                    $attributes[] = SessionAttributeCollection::make()
                        ->setLabel('irmaHeaderAttributeFullnameId')
                        ->add($field['irmaHeaderAttributeFullnameId']);
                    $attributes[] = SessionAttributeCollection::make()
                        ->setLabel('irmaHeaderAttributeBsnId')
                        ->add($field['irmaHeaderAttributeBsnId']);
                    if (!empty($field['irmaHeaderAttributeCity'])) {
                        $attributes[] = SessionAttributeCollection::make()
                            ->setLabel('irmaHeaderAttributeCity')
                            ->add($field['irmaHeaderAttributeCity']);
                    }
                    break;
            }
        }

        return new WP_REST_Response($this->client->getSession($attributes));
    }
}
