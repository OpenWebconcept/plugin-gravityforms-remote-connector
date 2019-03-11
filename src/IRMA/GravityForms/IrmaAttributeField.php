<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaAttributeField extends GF_Field {

    public $type = 'IRMA-attribute';

    public function get_form_editor_button() {
        return array(
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        );
    }

    public function get_form_editor_field_settings() {
        return array(
            'label_setting',
            'rules_setting',
            'visibility_setting',
        );
    }

    // een nieuwe klasse aanmaken voor het custom gedeelte van het form field.
    // een addAction functie gooien vanuit loader.php
    
}
?>