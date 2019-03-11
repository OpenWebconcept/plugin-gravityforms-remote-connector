<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaAttributeField extends GF_Field
{
    public $type = 'IRMA-attribute';

    public function get_form_editor_button()
    {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function validate($value, $form)
    {
        $this->failed_validation = false;
    }

    public function get_form_editor_field_settings()
    {
        return [
            'label_setting',
            'rules_setting',
            'visibility_setting',
        ];
    }

    // een nieuwe klasse aanmaken voor het custom gedeelte van het form field.
    // een addAction functie gooien vanuit loader.php
}
