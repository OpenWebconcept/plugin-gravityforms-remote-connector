<?php

namespace Yard\GravityForms;

use Exception;
use Yard\Connector\ConnectorEntity;
use Yard\Connector\ConnectorManager;
use Yard\OpenZaak\OpenZaakFormObject;

class GravityFormsFormHandler
{
    /**
     * Execute the external call.
     *
     * @param array $entry
     * @param array $form
     *
     * @return false|array|Exception
     */
    public function execute(array $entry, array $form)
    {
        $connectorEntity = $this->getConnectorEntity($form);
        if (!$connectorEntity->getConnector()->shouldProcess()) {
            return false;
        }

        $formID          = $this->getFormID($form);
        $payload         = OpenZaakFormObject::make($form['fields'], $entry)->toArray();
        try {
            $response = $connectorEntity->getConnector()->send($payload);
        } catch (Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }

    /**
     * Get the connectorEntity associated with the form.
     *
     * @param array $form
     *
     * @return ConnectorEntity
     */
    protected function getConnectorEntity($form): ConnectorEntity
    {
        return ConnectorManager::make()->find($form);
    }

    protected function getFormID(array $form): int
    {
        return $form['fields'][0]['formId'] ?? null;
    }
}
