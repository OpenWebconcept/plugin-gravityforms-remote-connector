<?php

namespace Yard\IRMA;

use Yard\IRMA\GravityForms\Actions\ExternalCall;
use Yard\IRMA\GravityForms\Settings\Loader;

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
        add_action('gform_loaded', [new Loader, 'load'], 5);
    }
}
