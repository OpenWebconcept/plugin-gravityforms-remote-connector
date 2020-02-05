<form id="openzaak-settings-form">
	<div id="" class="gaddon-section gaddon-first-section">
		<h4 class="gaddon-section-title gf_settings_subgroup_title">OpenZaak attributen</h4>
		<table class="form-table gforms_form_settings">
			<tbody>
				<tr id="gaddon-setting-row-openzaak_attribute">
					<th>
						<?php _e('Attribuut toevoegen', 'irma'); ?>
					</th>
					<td>
						<input type="text" name="openzaak_attribute_name" id="openzaak_attribute_name" class="openzaak-form-input" placeholder="<?php _e('Attribuut naam', 'irma'); ?>" required>
						<input type="text" name="openzaak_attribute_value" id="openzaak_attribute_value" class="openzaak-form-input" placeholder="<?php _e('Attribuut waarde', 'irma'); ?>" required>
					</td>
				</tr>
			</tbody>
		</table>
		<div class="openzaak-form__footer">
			<a href="#" id="openzaak_save_attributes" class="button-primary">
				Attribuut toevoegen
			</a>
			<div id="openzaak-form-attributes-notification"></div>
		</div>
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
				var form = $('#openzaak-settings-form');
				var empty = validateForm();

				if (empty) {
					return;
				}

				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php'); ?>',
					method: 'POST',
					data: {
						action: 'openzaak_store_attribute',
						security: '<?php echo wp_create_nonce('openzaak_store_attribute'); ?>',
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
