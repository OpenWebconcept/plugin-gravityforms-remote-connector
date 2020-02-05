<?php

namespace Yard\GravityForms;

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
     * @return false|array
     */
    public function execute(array $entry, array $form)
    {
        $connectorEntity = $this->getConnectorEntity($form);
        if (!$connectorEntity->getConnector()->shouldProcess()) {
            return false;
        }

        dd($connectorEntity);

        $formID          = $form['fields'][0]['formId'];
        $payload         = OpenZaakFormObject::make($form['fields'], $entry)->toJson();

        $connectorEntity->getConnector()->send();
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
}
