<script type='text/javascript'>
	fieldSettings["IRMA-attribute"] += ', .irma_attribute';
	fieldSettings["IRMA-launch-QR"] += ', .irma_qr_popup, .irma_qr_button_label';

	jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
		jQuery('.irma_qr_popup').change(function(event) {
			SetFieldProperty('irmaPopup', jQuery(this).attr('checked') !== undefined);
		});

		setTimeout(function() {
			jQuery("#field_" + field.id + " .irma_attribute_field").val(field["irmaAttribute"]);
			jQuery("#field_" + field.id + " .irma_qr_button_label").val(field["irmaButtonLabel"]);
			jQuery("#field_" + field.id + " .irma_qr_popup").attr('checked', !!field["irmaPopup"]);
		}, 0);
	});
</script>
