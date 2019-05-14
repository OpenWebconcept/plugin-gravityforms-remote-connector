<?php
/**
 * @var \IRMA\WP\Settings\SettingsManager $settings
 * @var string $irmaLogoUrl
 */
?>
<div id="irma-settings-wrapper" class="wrap">
	<div class="pd-irma-header">
		<img src="<?php echo $irmaLogoUrl; ?>" class="pd-irma-logo" />
		<h1 class="pd-irma-page-title">
			IRMA Configuration
		</h1>
	</div>

	<form id="irma-settings-form" class="pd-irma-form">

		<div class="pd-irma-block">
			<label for="irma_server_endpoint_url" class="irma-form-label">IRMA-server Endpoint</label>
			<input type="url" name="irma_server_endpoint_url" id="irma_server_endpoint_url" value="<?php echo $settings->getEndpointUrl(); ?>" class="irma-form-input">
		</div>

		<div class="pd-irma-form__footer">
			<a href="#" id="irma_save_settings" class="pd-irma-button">
				Save
			</a>
			<div id="pd-irma-form-notification"></div>
		</div>
	</form>
</div>

<script>
	(function($) {

		var createNotification = function(message, type) {
			var typeClass;

			if (type == 'error') {
				typeClass = 'pd-irma-notice--error'
			} else if (type == 'success') {
				typeClass = 'pd-irma-notice--success';
			} else {
				typeClass = 'pd-irma-notice--success';
			}

			var notification = $('<div class="pd-irma-notice ' + typeClass + '">' + message + ' </div>');
			$('#pd-irma-form-notification').html(notification).hide().fadeIn();

			setTimeout(function() {
				notification.fadeOut();
			}, 5000);
		}

		$('#irma_save_settings').click(function(e) {
			e.preventDefault();
			e.stopPropagation();

			var form = $('#irma-settings-form');

			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				method: 'POST',
				data: {
					action: 'irma_store_settings',
					security: '<?php echo wp_create_nonce('irma_store_settings'); ?>',
					data: form.serialize()
				}
			}).done(function(response) {
				createNotification(response.data.message, 'success');
			}).fail(function(err) {
				createNotification(err.responseJSON.data.message, 'error');
			});
		})
	})(jQuery);
</script>
