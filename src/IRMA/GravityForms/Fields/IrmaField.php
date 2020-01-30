<?php

namespace Yard\IRMA\GravityForms\Fields;

use GF_Field;

class IrmaField extends GF_Field
{
    /**
     * Render view for IRMA fields
     *
     * @param string $name
     * @param array $args
     * @return string
     */
    public function renderView(string $name, array $args): string
    {
        extract($args);
        ob_start();
        require __DIR__ . "/resources/{$name}.php";

        return ob_get_clean();
    }
}
