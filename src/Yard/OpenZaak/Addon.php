<?php

namespace Yard\OpenZaak;

use GFAddOn;

class Addon extends GFAddOn
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
    protected $_title = 'Gravityforms OpenZaak Add-On';

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

    public function render_uninstall()
    {
        require_once(__DIR__.'/views/add-attributes.php');
        parent::render_uninstall();
    }

    public function plugin_settings_fields()
    {
        return [
            [
                'title'  => esc_html__('OpenZaak Settings', 'irma'),
                'fields' => [
                    [
                        'name'              => 'openzaak_url',
                        'label'             => esc_html__('OpenZaak url', 'irma'),
                        'type'              => 'text',
                        'class'             => 'medium',
                        'feedback_callback' => [
                            $this, 'is_valid_setting'
                        ],
                    ],
                    [
                        'name'              => 'openzaak_rsin',
                        'label'             => esc_html__('RSIN identifier', 'irma'),
                        'type'              => 'text',
                        'class'             => 'medium',
                        'feedback_callback' => [
                            $this, 'is_valid_setting'
                        ],
                    ],
                    [
                        'name'  => 'openzaak_attributes',
                        'label' => esc_html__('Attributen', 'irma'),
                        'type'  => 'repeatable_group',
                        'args'  => [
                            [
                                'name'          => 'list',
                                'type'          => 'list'
                            ],
                        ],
                    ]
                ]
            ]
        ];
    }

    /**
    * Define the markup for the repeatable_group type field.
    *
    * @param array $field The field properties.
    */
    public function settings_repeatable_group($field)
    {
        foreach ($field['args'] as $field) {
            if (!method_exists('GFAddOn', 'settings_'. $field['type'])) {
                $class = "\Yard\OpenZaak\Settings\Fields\\" . ucfirst($field['type']) ."Field";
                if (class_exists($class) && (method_exists($class, 'render'))) {
                    echo (new $class($field))->render();
                }
            } else {
                $this->settings_text($field);
            }
        }
    }
}
