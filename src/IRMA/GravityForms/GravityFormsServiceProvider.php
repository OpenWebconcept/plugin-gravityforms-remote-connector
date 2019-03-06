<?php

namespace IRMA\WP\GravityForms;

use GF_Fields;
use IRMA\WP\Foundation\ServiceProvider;
//use IRMA\WP\GravityForms\IRMAFieldAddOn;

class GravityFormsServiceProvider extends ServiceProvider
{

    public function register()
    {
        // define( 'GF_IRMA_FIELD_ADDON_VERSION', '1.0' );

        // add_action( 'gform_loaded', [IRMAFieldAddOn::class, 'load'], 5 );
 
        GF_Fields::register(new IrmaAttributeField);
        GF_Fields::register(new IrmaLaunchQR);
    //    require_once( __DIR__.'/includes/class-IRMAFieldAddOn.php' );
 
        // \GFAddOn::register( 'GFIrmaFieldAddOn' );

        // Class voor settings page

    }

}