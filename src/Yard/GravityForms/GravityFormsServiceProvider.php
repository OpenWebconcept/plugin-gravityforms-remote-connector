<?php

namespace Yard\GravityForms;

use GFAddOn;
use Yard\Foundation\ServiceProvider;

class GravityFormsServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities for GravityForms.
     */
    public function register()
    {
        add_action('gform_after_submission', [new GravityFormsFormHandler(), 'execute'], 10, 2);

        GFAddOn::register(\Yard\GravityForms\GravityFormsAddon::class);

        GravityFormsAddon::get_instance();
    }
}
