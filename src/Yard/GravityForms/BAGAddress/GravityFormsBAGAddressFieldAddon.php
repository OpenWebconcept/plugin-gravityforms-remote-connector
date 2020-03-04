<?php

namespace Yard\GravityForms\BAGAddress;

use GFAddOn;

class GravityFormsBAGAddressFieldAddon extends GFAddOn
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
    protected $_slug = 'bag-address';

    /**
     * Relative path to the plugin from the plugins folder.
     *
     * @var string
     */
    protected $_path = GF_R_C_ROOT_PATH .'/plugin.php';

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
    protected $_title = 'GravityForms BAG Address Add-On';

    /**
     * The short title of the Add-On to be used in limited spaces.
     *
     * @var string
     */
    protected $_short_title = 'BAG Address';

    /**
     * Instance object
     *
     * @var self
     */
    private static $_instance = null;

    /**
     * Singleton loader.
     *
     * @return self
     */
    public static function get_instance(): self
    {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Include my_script.js when the form contains a 'simple' type field.
     *
     * @return array
     */
    public function scripts()
    {
        $scripts = [
            [
                'handle'  => 'bag_address',
                'src'     => plugin_dir_url(GF_R_C_PLUGIN_FILE) . 'resources/js/bag-address.js',
                // 'version' => $this->_version,
                'strings' => [
                    'ajaxurl' => admin_url('admin-ajax.php')
                ],
                'deps'    => ['jquery'],
                'enqueue' => [
                    [
                        'field_types' => ['bag-address']
                    ],
                ],
            ],

        ];

        return array_merge(parent::scripts(), $scripts);
    }
}
