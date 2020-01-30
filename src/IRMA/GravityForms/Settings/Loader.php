<?php

namespace Yard\IRMA\GravityForms\Settings;

use GFAddOn;
use Yard\IRMA\GravityForms\Settings\Settings;

class Loader
{
    public static function load()
    {
        if (! method_exists('GFForms', 'include_addon_framework')) {
            return;
        }

        GFAddOn::register('Yard\IRMA\GravityForms\Settings\Settings');

        Settings::get_instance();
    }
}
