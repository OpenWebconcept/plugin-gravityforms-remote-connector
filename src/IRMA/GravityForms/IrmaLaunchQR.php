<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaLaunchQR extends GF_Field {

    public $type = 'IRMA-launch-QR';

    public function get_form_editor_button() {
        return array(
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        );
    }
    
}

?>