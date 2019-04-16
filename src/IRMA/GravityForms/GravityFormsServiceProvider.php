<?php

namespace IRMA\WP\GravityForms;

use GF_Fields;
use IRMA\WP\Foundation\ServiceProvider;

class GravityFormsServiceProvider extends ServiceProvider
{
    public function register()
    {
        add_action('rest_api_init', function () {
            register_rest_route('irma/v1', '/gf/handle', [
                'methods' => 'POST',
                'callback' => [new API\ResultHandler, 'handle'],
            ]);
        });


        add_action('wp_head', function () {
            ?>
			<script type="text/javascript">
				var irma_ajaxurl = '<?php echo admin_url("admin-ajax.php"); ?>';
				var irma_ajaxnonce = '<?php echo wp_create_nonce("itr_ajax_nonce"); ?>';
			</script><?php
        });

        // define( 'GF_IRMA_FIELD_ADDON_VERSION', '1.0' );

        // add_action( 'gform_loaded', [IRMAFieldAddOn::class, 'load'], 5 );
        add_action('gform_enqueue_scripts', [$this, 'enqueueScripts'], 10, 2);

        GF_Fields::register(new IrmaAttributeField);
        GF_Fields::register(new IrmaLaunchQR);
        //    require_once( __DIR__.'/includes/class-IRMAFieldAddOn.php' );

        // \GFAddOn::register( 'GFIrmaFieldAddOn' );
    }

    public function enqueueScripts($form, $is_ajax)
    {
        wp_register_script('irma-gf-js', $this->plugin->resourceUrl('irma-gf.js'), ['jquery']);

        $url = 'https://metrics.privacybydesign.foundation/irmaserver/session';

        $request = wp_remote_post($url, [
            'method' => 'POST',
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'type' => 'disclosing',
                'content' => [
                    [
                        'label' => 'Naam',
                        'attributes' => [
                            'irma-demo.nijmegen.personalData.fullname'
                        ]
                    ],
                    [
                        'label' => 'BSN',
                        'attributes' => [
                            'irma-demo.nijmegen.bsn.bsn'
                        ]
                    ],
                    [
                        'label' => 'Straat',
                        'attributes' => [
                            'irma-demo.nijmegen.address.street',
                        ],
                    ],
                    [
                        'label' => 'Huisnummer',
                        'attributes' => [
                            'irma-demo.nijmegen.address.houseNumber'
                        ],
                    ],
                    [
                        'label' => 'Woonplaats',
                        'attributes' => [
                            'irma-demo.nijmegen.address.city'
                        ],
                    ],
                ]
            ])
        ]);

        $body = json_decode(wp_remote_retrieve_body($request));

        wp_localize_script('irma-gf-js', 'irma_gf', [
            'handle_url' => get_rest_url(null, 'irma/v1/gf/handle'),
            'form_'.$form['id'] => $body
        ]);

        wp_enqueue_script('irma-gf-js');
    }
}
