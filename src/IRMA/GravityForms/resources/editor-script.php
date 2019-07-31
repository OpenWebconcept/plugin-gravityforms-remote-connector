<script type='text/javascript'>
	fieldSettings["IRMA-attribute"] += ', .irma_attribute';
	fieldSettings["IRMA-header"] += ', .irma_header';
	fieldSettings["IRMA-header"] += ', .irmaHeaderButtonLabel';
	fieldSettings["IRMA-header"] += ', .irmaHeaderText';
	fieldSettings["IRMA-header"] += ', .irmaHeaderAttributeFullnameId';
	fieldSettings["IRMA-header"] += ', .irmaHeaderAttributeBsnId';
	fieldSettings["IRMA-header"] += ', .irmaPopup';
	fieldSettings["IRMA-launch-QR"] += ', .irma_qr_popup, .irma_qr_button_label';

	jQuery(document).bind("gform_load_field_settings", function(event, field, form) {
		jQuery('.irma_qr_popup').change(function(event) {
			SetFieldProperty('irmaPopup', jQuery(this).attr('checked') !== undefined);
		});

		jQuery('.irma_header_qr_popup').change(function(event) {
			SetFieldProperty('irmaPopup', jQuery(this).attr('checked') !== undefined);
		});

		setTimeout(function() {
			jQuery("#field_" + field.id + " .irma_attribute_field").val(field["irmaAttribute"]);
			jQuery("#field_" + field.id + " .irma_header_field").val(field["irmaHeader"]);
			jQuery("#field_" + field.id + " .irma_header_button_label").val(field["irmaHeaderButtonLabel"]);
			jQuery("#field_" + field.id + " .irma_header_field").val(field["irmaHeaderText"]);
			jQuery("#field_" + field.id + " .irma_header_qr_popup").attr('checked', !!field["irmaPopup"]);
			jQuery("#field_" + field.id + " .irma_header_attribute_fullname_id").val(field["irmaHeaderAttributeFullnameId"]);
			jQuery("#field_" + field.id + " .irma_header_attribute_bsn_id").val(field["irmaHeaderAttributeBsnId"]);
			jQuery("#field_" + field.id + " .irma_qr_button_label").val(field["irmaButtonLabel"]);
			jQuery("#field_" + field.id + " .irma_qr_popup").attr('checked', !!field["irmaPopup"]);
		}, 0);
	});
</script>