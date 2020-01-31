<form id="openzaak-settings-form">
	<label class="openzaak-form-label"><?php _e('Add Openzaak attribute', 'irma'); ?></label>
	<input type="text" name="openzaak_attribute_name" id="openzaak_attribute_name" class="openzaak-form-input" placeholder="<?php _e('Attribute name', 'irma'); ?>" required>
	<input type="text" name="openzaak_attribute_value" id="openzaak_attribute_value" class="openzaak-form-input" placeholder="<?php _e('Attribute value', 'irma'); ?>" required>

	<div class="openzaak-form__footer">
		<a href="#" id="openzaak_save_attributes" class="button-primary">
			Save
		</a>
		<div id="openzaak-form-attributes-notification"></div>
	</div>
</form>

<script>
	(function($) {
		$(document).ready(function() {

			// validate the openzaak form input fields.
			var validateForm = function() {
				empty = false;
				$('#openzaak-settings-form input[type="text"]').each(function() {
					if (!$(this).val()) {
						empty = true;
						$(this).addClass('input-empty');
					} else {
						$(this).removeClass('input-empty');
					}
				});

				return empty;
			}

			// create notification after submitting a form.
			var createNotification = function(message, type) {
				var typeClass;

				if (type == 'error') {
					typeClass = 'openzaak-notice--error'
				} else if (type == 'success') {
					typeClass = 'openzaak-notice--success';
				} else {
					typeClass = 'openzaak-notice--success';
				}

				var notification = $('<div class="openzaak-notice ' + typeClass + '">' + message + ' </div>');

				$('#openzaak-form-attributes-notification').html(notification).hide().fadeIn();

				setTimeout(function() {
					notification.fadeOut();
				}, 5000);
			}

			// onclick event for openzaak attributes.
			$('#openzaak_save_attributes').click(function(e) {
				// e.preventDefault();
				// e.stopPropagation();

				var form = $('#openzaak-settings-form');

				var empty = validateForm();

				if (empty) {
					return;
				}

				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					method: 'POST',
					data: {
						action: 'openzaak_store_settings',
						security: '<?php echo wp_create_nonce('openzaak_store_settings'); ?>',
						data: form.serialize()
					}
				}).done(function(response) {
					createNotification(response.data.message, 'success');
					$("#openzaak_attribute_name").val('');
					$("#openzaak_attribute_value").val('');
					location.reload();
				}).fail(function(err) {
					createNotification(err.responseJSON.data.message, 'error');
				});
			});
		});
	})(jQuery);

</script>
