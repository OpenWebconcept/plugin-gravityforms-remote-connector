<?php 

namespace IRMA\WP\GravityForms;

class IRMAFieldAddOn {
 
    public static function load() {
        if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
            return;
        }

        require_once( 'includes/class-IRMAFieldAddOn.php' );
 
        GFAddOn::register( 'GFIrmaFieldAddOn' );
    }
 
}

?>