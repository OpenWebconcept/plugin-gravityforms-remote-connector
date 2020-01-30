<?php

namespace Yard\IRMA\GravityForms\OpenZaak;

use GFAddOn;

class OpenZaakAddon extends GFAddOn
{
    /**
         * Version number.
         *
         * @var string
         */
    protected $_version = '1.0';

    /**
     * Minimal required GF version.
     *
     * @var string
     */
    protected $_min_gravityforms_version = '2.4';

    /**
     * Subview slug.
     *
     * @var string
     */
    protected $_slug = 'openzaak-addon';

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
    protected $_title = 'Gravityforms Openzaak Add-On';

    /**
     * The short title of the Add-On to be used in limited spaces.
     *
     * @var string
     */
    protected $_short_title = 'OpenZaak';

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

    public function plugin_settings_fields()
    {
        return [
            [
                'title'  => esc_html__('Openzaak Settings', 'irma'),
                'fields' => [
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
                ]
            ]
        ];
    }
}
