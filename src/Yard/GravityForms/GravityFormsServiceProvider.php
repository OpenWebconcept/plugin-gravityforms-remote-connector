<?php

namespace Yard\GravityForms;

use GFAddOn;
use GFForms;
use GF_Fields;
use Yard\Foundation\ServiceProvider;
use Yard\GravityForms\BAGAddress\BAGLookup;
use Yard\GravityForms\BAGAddress\Fields\BAGAddressField;

class GravityFormsServiceProvider extends ServiceProvider
{
    /**
     * Register all necessities for GravityForms.
     */
    public function register()
    {
        add_action('gform_after_submission', [new GravityFormsFormHandler(), 'execute'], 10, 2);

        if (! method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        GFForms::include_addon_framework();

        GFAddOn::register(\Yard\GravityForms\GravityFormsAddon::class);

        GravityFormsAddon::get_instance();

        add_action('wp_ajax_no_priv_bag_address_lookup', [new BAGLookup, 'execute']);
        add_action('wp_ajax_bag_address_lookup', [new BAGLookup, 'execute']);

        add_action('gform_loaded', function () {
            GF_Fields::register(new BAGAddressField());
        }, 5);
    }
}
