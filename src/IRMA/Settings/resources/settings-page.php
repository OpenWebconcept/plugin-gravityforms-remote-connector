<?php

/**
 * @var \Yard\IRMA\Settings\SettingsManager
 * @var string                            irmaLogoUrl
 */
?>
<div id="irma-settings-wrapper container" class="wrap">
	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="pd-irma-header">
				<img src="<?php echo $irmaLogoUrl; ?>" class="pd-irma-logo" />
				<h1 class="pd-irma-page-title">
					IRMA Configuration
				</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5 col-sm-5 col-xs-12">
			<form id="irma-settings-form" class="pd-irma-form">



				<div class="pd-irma-block">
					<label for="irma_wp_rsin" class="irma-form-label"><?php _e('RSIN', 'irma_wp'); ?></label>
					<input type="text" name="irma_wp_rsin" id="irma_wp_rsin" value="<?php echo $settings->getRISN(); ?>" class="irma-form-input">
				</div>

				<div class="pd-irma-block">
					<label for="createCaseURL" class="irma-form-label"><?php _e('create case URL', 'irma_wp'); ?></label>
					<input type="text" name="createCaseURL" id="createCaseURL" value="<?php echo $settings->createCaseURL(); ?>" class="irma-form-input">
				</div>

				<div class="pd-irma-block">
					<label for="createCaseObjectURL" class="irma-form-label"><?php _e('create case object URL', 'irma_wp'); ?></label>
					<input type="text" name="createCaseObjectURL" id="createCaseObjectURL" value="<?php echo $settings->createCaseObjectURL(); ?>" class="irma-form-input">
				</div>

				<div class="pd-irma-block">
					<label for="createCasePropertyURL" class="irma-form-label"><?php _e('create case property URL', 'irma_wp'); ?></label>
					<input type="text" name="createCasePropertyURL" id="createCasePropertyURL" value="<?php echo $settings->createCasePropertyURL(); ?>" class="irma-form-input">
				</div>
			</form>
		</div>

		<div class="col-md-7 col-sm-7 col-xs-12">
			<div class="col-md-12 col-sm-12 col-xs-12 pl-0">
				<div class="pd-irma-block">
					<label class="irma-form-label">Overview</label>
					<table id="decos-attributes-overview" class="table-responsive"></table>
				</div>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12 pl-0">
				<form id="irma-settings-decos-form" class="pd-irma-form">
					<div class="pd-irma-block">
						<label class="irma-form-label"><?php _e('Add Decos attribute', 'irma_wp'); ?></label>
						<input type="text" name="irma_wp_decos_attribute_name" id="irma_wp_decos_attribute_name" class="irma-form-input" placeholder="<?php _e('Attribute name', 'irma_wp'); ?>" required>
						<input type="text" name="irma_wp_decos_attribute_value" id="irma_wp_decos_attribute_value" class="irma-form-input" placeholder="<?php _e('Attribute value', 'irma_wp'); ?>" required>
					</div>

					<div class="pd-irma-form__footer">
						<a href="#" id="irma_save_decos_attributes" class="pd-irma-button">
							Save
						</a>
						<div id="pd-irma-form-decos-attributes-notification"></div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	(function($) {
		// create decos attributes overview when page is loaded.
		$(document).ready(function() {
			$.ajax({
				url: '<?php echo admin_url('admin-ajax.php'); ?>',
				method: 'POST',
				data: {
					action: 'irma_get_settings_decos',
				}
			}).done(function(response) {
				createDecosAttributesOverview(response.data.decosAttributes);
			})
			// });

			// create notification after submitting a form.
			var createNotification = function(message, type, settingsType) {
				var typeClass;

				if (type == 'error') {
					typeClass = 'pd-irma-notice--error'
				} else if (type == 'success') {
					typeClass = 'pd-irma-notice--success';
				} else {
					typeClass = 'pd-irma-notice--success';
				}

				var notification = $('<div class="pd-irma-notice ' + typeClass + '">' + message + ' </div>');

				if (settingsType == 'irma_settings') {
					$('#pd-irma-form-notification').html(notification).hide().fadeIn();
				}

				if (settingsType == 'irma_decos_settings') {
					$('#pd-irma-form-decos-attributes-notification').html(notification).hide().fadeIn();
				}

				setTimeout(function() {
					notification.fadeOut();
				}, 5000);
			}

			// create decos attributes overview.
			var createDecosAttributesOverview = function(decosAttributes) {
				console.log(decosAttributes);
				var deleteIcon = '<?php echo $deleteIcon; ?>';
				$('#decos-attributes-overview').empty();
				$('#decos-attributes-overview').append('<tr><th>Property name</th><th>Property value</th><th>Delete</th></tr>');
				let attributesOverview = $(decosAttributes).each(function(index, element) {
					let tableRow = '<tr><td>' + element['caseProperty'] + '</td><td>' + element['casePropertyValue'] + '</td><td class="delete-decos-attribute" data-case-property="' + element['caseProperty'] + '" ><img src="' + deleteIcon + '" /></td></tr>';
					$('#decos-attributes-overview').append(tableRow);
				});
			}

			// validate the decos form input fields.
			var validateForm = function() {
				empty = false;
				$('#irma-settings-decos-form input[type="text"]').each(function() {
					if (!$(this).val()) {
						empty = true;
						$(this).addClass('input-empty');
					} else {
						$(this).removeClass('input-empty');
					}
				});

				return empty;
			}

			// delete decos attribute.
			$("#decos-attributes-overview").on("click", "td", function() {
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					method: 'POST',
					data: {
						traditional: true,
						action: 'irma_delete_setting_decos',
						data: $(this).attr('data-case-property'),
					}
				}).done(function(response) {
					createDecosAttributesOverview(response.data.decosAttributes);
				})
			})

			// onclick event for irma form settings.
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
					createNotification(response.data.message, 'success', 'irma_settings');
				}).fail(function(err) {
					createNotification(err.responseJSON.data.message, 'error', 'irma_settings');
				});
			})

			// onclick event for decos attributes.
			$('#irma_save_decos_attributes').click(function(e) {
				e.preventDefault();
				e.stopPropagation();

				var form = $('#irma-settings-decos-form');

				var empty = validateForm();

				if (empty) {
					return;
				}

				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					method: 'POST',
					data: {
						action: 'irma_store_settings_decos',
						security: '<?php echo wp_create_nonce('irma_store_settings_decos'); ?>',
						data: form.serialize()
					}
				}).done(function(response) {
					createNotification(response.data.message, 'success', 'irma_decos_settings');
					createDecosAttributesOverview(response.data.decosAttributes);
					$("#irma_wp_decos_attribute_name").val('');
					$("#irma_wp_decos_attribute_value").val('');

				}).fail(function(err) {
					createNotification(err.responseJSON.data.message, 'error', 'irma_decos_settings');
				});
			})
		});
	})(jQuery);

</script>
