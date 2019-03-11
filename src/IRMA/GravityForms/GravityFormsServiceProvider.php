<?php

namespace IRMA\WP\GravityForms;

use GF_Fields;
use IRMA\WP\Foundation\ServiceProvider;

class GravityFormsServiceProvider extends ServiceProvider
{

    public function register()
    {
        GF_Fields::register(new IrmaAttributeField);
        GF_Fields::register(new IrmaLaunchQR);
    }

}