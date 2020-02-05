<?php

namespace Yard\OpenZaak\Settings\Fields;

class LinkField
{
    /**
     * Render the field.
     *
     * @return string
     */
    public function render(): string
    {
        return '<a href="#">delete</a>';
    }
}
