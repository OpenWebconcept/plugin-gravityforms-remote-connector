<?php

namespace Yard\IRMA\GravityForms;

use GFAddOn;

class IrmaAddOn extends GFAddOn
{
    protected $_version = IRMA_WP_VERSION;
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'irma-addon';
    protected $_path = 'irma-wp/plugin.php';
    protected $_full_path = __FILE__;
    protected $_title = 'GravityForms IRMA Add-On';
    protected $_short_title = 'IRMA Add-On';

    private static $_instance = null;

    public static function get_instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function init_admin()
    {
        parent::init_admin();
        add_filter('gform_tooltips', [$this, 'filter_gform_tooltips'], 10, 1);
        add_action('gform_field_standard_settings', [$this, 'field_appearance_settings'], 10, 2);
        add_action('gform_editor_js', [$this, 'editor_script']);
    }

    /**
     * Add the custom setting to the appearance tab.
     *
     * @param int $position the position the settings should be located at
     * @param int $formId   the ID of the form currently being edited
     */
    public function field_appearance_settings($position, $formId)
    {
        if ($position == 350) {
            require __DIR__.'/resources/irma-form-settings.php';
        }
    }

    /**
     * Scripting necessary for the form editor.
     *
     * @return string
     */
    public function editor_script()
    {
        require __DIR__.'/resources/editor-script.php';
    }

    public function filter_gform_tooltips($tooltips)
    {
        $tooltips['irma_header_attribute_fullname_id'] = esc_html('Fill in the ID of the field used for the fullname.', 'irma-wp');
        $tooltips['irma_header_attribute_bsn_id'] = esc_html('Fill in the ID of the field used for the BSN.', 'irma-wp');
        $tooltips['irma_header_city_id'] = esc_html('Fill in the ID of the field used for the city.', 'irma-wp');
        $tooltips['irma_header_city'] = esc_html('Fill in the city that you would like to check on.', 'irma-wp');

        return $tooltips;
    }
}
