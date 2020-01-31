<script type='text/javascript'>
	// To display custom field under each type of Gravity Forms field
	jQuery.each(fieldSettings, function(index, value) {
		fieldSettings[index] += ", .highlight_setting";
	});

	// store the custom field with associated Gravity Forms field
	jQuery(document).bind("gform_load_field_settings", function(event, field, form) {

		// save field value: Start Section B
		jQuery("#case_property").val(field["casePropertyName"]);
		// End Section B

	});

</script>
