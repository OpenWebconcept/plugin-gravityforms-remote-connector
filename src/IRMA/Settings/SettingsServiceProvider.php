<?php

namespace IRMA\WP\Settings;

use IRMA\WP\Foundation\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{

	public function register()
	{
		add_action('wp_ajax_irma_store_settings', [new StoreSettingsHandler, 'handle']);

		add_action('admin_menu', function () {
			add_menu_page('irma', 'IRMA', 'manage_options', 'irma', function () {
				$settings = new SettingsManager;
				$irmaLogoUrl = $this->plugin->resourceUrl('img/irma_logo.png');

				require __DIR__ . '/resources/settings-page.php';
			}, $this->plugin->resourceUrl('img/irma_icon.png'), 300);
		});
	}
}
