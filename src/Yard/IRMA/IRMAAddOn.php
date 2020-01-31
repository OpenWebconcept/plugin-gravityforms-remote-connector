<?php

namespace Yard\IRMA;

use GFAddOn;

class IRMAAddOn extends GFAddOn
{
    /**
     * Version number.
     *
     * @var string
     */
    protected $_version = IRMA_WP_VERSION;

    /**
     * Minimal required GF version.
     *
     * @var string
     */
    protected $_min_gravityforms_version = '1.9';

    /**
     * Subview slug.
     *
     * @var string
     */
    protected $_slug = 'irma-addon';

    /**
     * Relative path to the plugin from the plugins folder.
     *
     * @var string
     */
    protected $_path = 'irma-wp/plugin.php';

    /**
     * The physical path to the main plugin file.
     *
     * @var string
     */
    protected $_full_path = __FILE__;

    /**
     * The complete title of the Add-On.
     *
     * @var string
     */
    protected $_title = 'GravityForms IRMA';

    /**
     * The short title of the Add-On to be used in limited spaces.
     *
     * @var string
     */
    protected $_short_title = 'IRMA';

    /**
     * Instance object
     *
     * @var self
     */
    private static $_instance = null;

    public static function get_instance()
    {
        if (null == self::$_instance) {
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
        if (350 == $position) {
            require __DIR__.'/GravityForms/Fields/resources/irma-form-settings.php';
        }
    }

    /**
     * Scripting necessary for the form editor.
     *
     * @return string
     */
    public function editor_script()
    {
        require __DIR__.'/GravityForms/Fields/resources/editor-script.php';
    }

    public function filter_gform_tooltips($tooltips)
    {
        $tooltips['irma_header_attribute_fullname_id'] = esc_html('Fill in the ID of the field used for the fullname.', 'irma-wp');
        $tooltips['irma_header_attribute_bsn_id']      = esc_html('Fill in the ID of the field used for the BSN.', 'irma-wp');
        $tooltips['irma_header_city_id']               = esc_html('Fill in the ID of the field used for the city.', 'irma-wp');
        $tooltips['irma_header_city']                  = esc_html('Fill in the city that you would like to check on.', 'irma-wp');

        return $tooltips;
    }

    public function plugin_settings_fields()
    {
        return [
            [
                'title'  => esc_html__('Irma Settings', 'irma'),
                'fields' => [
                    [
                        'name'              => 'irma_server_endpoint',
                        'tooltip'           => esc_html__('Server endpoint', 'irma'),
                        'label'             => esc_html__('Server endpoint', 'irma'),
                        'type'              => 'text',
                        'class'             => 'medium',
                        'feedback_callback' => [
                            $this, 'is_valid_setting'
                        ],
                    ],
                    [
                        'name'              => 'irma_server_token',
                        'tooltip'           => esc_html__('Server token', 'irma'),
                        'label'             => esc_html__('Server token', 'irma'),
                        'type'              => 'text',
                        'class'             => 'medium',
                        'feedback_callback' => [
                            $this, 'is_valid_setting'
                        ],
                    ],
                    [
                        'name'              => 'irma_bsn_attribute_field_name',
                        'tooltip'           => esc_html__('BSN attribute veldnaam', 'irma'),
                        'label'             => esc_html__('BSN attribute veldnaam', 'irma'),
                        'type'              => 'text',
                        'class'             => 'medium',
                        'feedback_callback' => [
                            $this, 'is_valid_setting'
                        ],
                    ]
                ]
            ]
        ];
    }
}
