<?php

namespace Yard\GravityForms;

use GFAddOn;
use Yard\Connector\ConnectorManager;

class GravityFormsAddon extends GFAddOn
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
    protected $_slug = 'remote-connector-addon';

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
    protected $_title = 'GravityForms Remote Connector Add-On';

    /**
     * The short title of the Add-On to be used in limited spaces.
     *
     * @var string
     */
    protected $_short_title = 'Remote Connector';

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
     * Get all the registered connectors as field arrays.
     *
     * @return array
     */
    private function remoteConnectorChoicesAsArray(): array
    {
        $noConnector = [
            [
            'label' => esc_html__('Geen remote connector', config('core.text_domain')),
            'value' => 'no-remote-connector'
            ]
        ];
        $connectors = array_values(array_map(function ($connector) {
            return [
                'label' => $connector->getName(),
                'value' => $connector->getIdentifier(),
            ];
        }, ConnectorManager::make()->all()));

        return array_merge($noConnector, $connectors);
    }

    /**
    * Configures the settings which should be rendered on the Form Settings > Simple Add-On tab.
    *
    * @return array
    */
    public function form_settings_fields($form)
    {
        return [
        [
            'title'  => esc_html__('Remote Connector settings', config('core.text_domain')),
            'fields' => [
                [
                    'label'   => esc_html__('Registered Connectors', config('core.text_domain')),
                    'type'    => 'select',
                    'name'    => 'remote-connector-connectors',
                    'choices' => $this->remoteConnectorChoicesAsArray()
                ]
            ],
        ],
    ];
    }
}
