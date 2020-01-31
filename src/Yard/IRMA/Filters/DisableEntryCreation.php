<?php

namespace Yard\IRMA\Filters;

use GFAPI;
use Yard\IRMA\GravityForms\Fields\IrmaAttributeField;

class DisableEntryCreation
{

    /**
     * Prevent entries from being created,
     * when the form contains IRMA attributes.
     *
     * @param array $entry
     * @param array $form
     * @return void
     */
    public function apply(array $entry, array $form)
    {
        foreach ($form['fields'] as $field) {
            if ($field instanceof IrmaAttributeField) {
                GFAPI::delete_entry($entry['id']);
                break;
            }
        }
    }
}
