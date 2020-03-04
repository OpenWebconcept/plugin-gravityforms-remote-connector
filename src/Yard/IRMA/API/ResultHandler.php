<?php

namespace Yard\IRMA\API;

use GFAPI;
use OutOfBoundsException;
use Yard\IRMA\Client\IRMAClient;

class ResultHandler
{
    /**
     * @var IRMAClient
     */
    private $client;

    public function __construct(IRMAClient $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
		$formId = isset($_POST['formId']) ? absint($_POST['formId']) : null;
        if ( null === ($formId)) {
			throw new OutOfBoundsException('No form is requested');
		};
        $form   = GFAPI::get_form($formId);

        $token = $_POST['token'];

        $attributes = $this->client->setToken($token)->getResult();

        $result = [];

        foreach ($form['fields'] as $field) {
            if (('IRMA-attribute' != $field['type'] || !in_array($field['irmaAttribute'], $attributes->getIds())) && ('IRMA-header' != $field['type'])) {
                continue;
            }

            switch ($field['type']) {
                case 'IRMA-attribute':
                    $result[] = [
                        'input'     => 'input_' . $formId . '_' . $field['id'],
                        'label'     => $field['label'],
                        'attribute' => $field['irmaAttribute'],
                        'value'     => $attributes[$field['irmaAttribute']]->getValue(),
                    ];
                    break;
                case 'IRMA-header':
                    $result[] = [
                        'input'     => 'input_' . $formId . '_' . $field['id'],
                        'label'     => 'irmaHeaderAttributeFullnameId',
                        'attribute' => $field['irmaHeaderAttributeFullnameId'],
                        'value'     => $attributes[$field['irmaHeaderAttributeFullnameId']]->getValue(),
                    ];

                    $result[] = [
                        'input'     => 'input_' . $formId . '_' . $field['id'],
                        'label'     => 'irmaHeaderAttributeBsnId',
                        'attribute' => $field['irmaHeaderAttributeBsnId'],
                        'value'     => $attributes[$field['irmaHeaderAttributeBsnId']]->getValue(),
                    ];

                    if (!empty($field['irmaHeaderAttributeCity'])) {
                        $result[] = [
                            'input'     => 'input_' . $formId . '_' . $field['id'],
                            'label'     => 'irmaHeaderAttributeCity',
                            'attribute' => $field['irmaHeaderAttributeCity'],
                            'value'     => $attributes[$field['irmaHeaderAttributeCity']]->getValue(),
                        ];
                    }

                    break;
            }
        }

        set_transient('irma_result_' . $token, $result, WEEK_IN_SECONDS);

        return $result;
    }
}