<?php

namespace Yard;

use Yard\OpenZaak\Actions\ExternalCall;

class Actions
{
    public function __construct()
    {
        $this->addActions();
    }

    /**
     * Add actions
     *
     * @return void
     */
    protected function addActions()
    {
        add_action('gform_after_submission', [new ExternalCall(), 'externalCall'], 10, 2);
    }
}
