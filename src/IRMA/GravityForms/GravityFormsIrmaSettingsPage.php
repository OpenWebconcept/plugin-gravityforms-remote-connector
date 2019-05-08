<?php
namespace IRMA\WP\GravityForms;

use GFAddOn;

class GravityFormsIrmaSettingsPage extends GFAddOn
{
    protected $_version = '1.0';
    protected $_min_gravityforms_version = '1.9';
    protected $_slug = 'irma-settings-addon';
    protected $_path = 'irma-wp/plugin.php';
    protected $_full_path = __FILE__;
    protected $_title = 'IRMA settings';
    protected $_short_title = 'IRMA settings';

    /**
     * @var object|null $_instance If available, contains an instance of this class.
     */
    private static $_instance = null;

    /**
     * Returns an instance of this class, and stores it in the $_instance property.
     *
     * @return object $_instance An instance of this class.
     */
    public static function get_instance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Creates a custom page for this add-on.
     */
    // public function plugin_page()
    // {
    //     echo 'This page appears in the Forms menu';
    // }

    /**
     * Configures the settings which should be rendered on the Form Settings > Simple Add-On tab.
     *
     * @return array
     */
    public function form_settings_fields($form)
    {
        return [
            [
                'title'  => esc_html__('Irma Form Settings', 'simpleaddon'),
                'fields' => [
                    [
                        'label'             => esc_html__('URL to IRMA endpoint', 'simpleaddon'),
                        'type'              => 'text',
                        'name'              => 'endpointIRMA',
                        'tooltip'           => esc_html__('This is the tooltip', 'simpleaddon'),
                        'class'             => 'medium',
                        'feedback_callback' => array($this, 'validateField'),
                    ],
                ],
            ]
        ];
    }

    public function validateField($value)
    {
        $request = wp_remote_post($value, [
            'method' => 'GET',
        ]);

        if (is_wp_error($request)) {
            return false;
        }

        return $request['response']['code'] === 200;
    }
}
