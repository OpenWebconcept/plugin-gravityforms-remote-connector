<?php

GFForms::include_addon_framework();

class IRMAFieldAddOn extends GFAddOn {

    protected $_version = GF_IRMA_FIELD_ADDON_VERSION;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'irmafieldaddon';
    protected $_path = 'GravityForms/IRMAFieldAddOn.php';
    protected $_full_path = __FILE__;
    protected $_title = 'Gravity Forms IRMA Field Add-On';
    protected $_short_title = 'IRMA Field Add-On';


    private static $_instance = null;
 
    public static function get_instance() {
        if ( self::$_instance == null ) {
            self::$_instance = new self();
        }
    
        return self::$_instance;
    }

    public function pre_init() {
        parent::pre_init();
    
        if ( $this->is_gravityforms_supported() && class_exists( 'GF_Field' ) ) {
            require_once( 'includes/class-IRMAField.php' );
        }
    }

}

?>