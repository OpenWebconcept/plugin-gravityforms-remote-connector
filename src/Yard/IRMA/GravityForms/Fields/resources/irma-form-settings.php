<li class="irma_attribute field_setting">
	<label class="section_label" for="irma_attribute_list_label">
		<?php esc_html_e('IRMA Attribute', GF_R_C_PLUGIN_SLUG); ?>
		<?php gform_tooltip('irma_attributes'); ?>
	</label>
	<div class="irma_attribute_list">
		<input type="text" class="irma_attribute_field fieldwidth-3" onchange="SetFieldProperty('irmaAttribute', this.value);" />
	</div>
</li>

<li class="irma_header field_setting">
	<label class="section_label" for="irma_header_list_label">
		<?php esc_html_e('IRMA Header text', GF_R_C_PLUGIN_SLUG); ?>
	</label>
	<div class="irma_header_list">
		<input type="text" class="irma_header_field fieldwidth-3" onchange="SetFieldProperty('text', this.value);" />
	</div>
	<br />
	<div class="irma_qr_popup_container">
		<label class="section_label" for="irma_header_qr_popup">
			<?php esc_html_e('QR Code', GF_R_C_PLUGIN_SLUG); ?>
		</label>
		<input type="checkbox" id="irma_header_qr_popup" class="irma_header_qr_popup" />
		<label for="irma_header_qr_popup" class="inline">
			<?php esc_html_e('Display as pop-up?', GF_R_C_PLUGIN_SLUG); ?>
		</label>
	</div>
	<br />
	<label class="section_label" for="irma_qr_button_label">
		<?php esc_html_e('Button label', GF_R_C_PLUGIN_SLUG); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="text" id="irma_header_button_label" class="irma_header_button_label fieldwidth-3" onchange="SetFieldProperty('buttonLabel', this.value);" />
	</div>
	<br />
	<label class="section_label" for="irma_header_attribute_fullname_id_label">
		<?php esc_html_e('ID attribute fullname', GF_R_C_PLUGIN_SLUG); ?>
		<?php gform_tooltip('irma_header_attribute_fullname_id'); ?>
	</label>
	<div class="irma_header_attribute_fullname_id">
		<input type="text" class="irma_header_attribute_fullname_id fieldwidth-3" onchange="SetFieldProperty('irmaHeaderAttributeFullnameId', this.value);" />
	</div>
	<br />
	<label class="section_label" for="irma_header_attribute_bns_id_label">
		<?php esc_html_e('ID attribute BSN', GF_R_C_PLUGIN_SLUG); ?>
		<?php gform_tooltip('irma_header_attribute_bsn_id'); ?>
	</label>
	<div class="irma_header_attribute_bsn_id">
		<input type="text" class="irma_header_attribute_bsn_id fieldwidth-3" onchange="SetFieldProperty('irmaHeaderAttributeBsnId', this.value);" />
	</div>
	<br />
	<div class="irma_header_city_id_container">
		<label class="section_label" for="irma_header_city_id_label">
			<?php esc_html_e('ID attribute city', GF_R_C_PLUGIN_SLUG); ?>
			<?php gform_tooltip('irma_header_city_id'); ?>
		</label>
		<div class="irma_header_city_id">
			<input type="text" class="irma_header_city_id fieldwidth-3" onchange="SetFieldProperty('irmaHeaderAttributeCity', this.value);" />
		</div>
	</div>
	<br />
	<div class="irma_header_city_container">
		<label class="section_label" for="irma_header_city_label">
			<?php esc_html_e('City to check', GF_R_C_PLUGIN_SLUG); ?>
			<?php gform_tooltip('irma_header_city'); ?>
		</label>
		<div class="irma_header_city">
			<input type="text" class="irma_header_city fieldwidth-3" onchange="SetFieldProperty('irmaHeaderCity', this.value);" />
		</div>
	</div>
</li>

<li class="irma_qr field_setting">
	<label class="section_label" for="irma_qr_popup">
		<?php esc_html_e('QR Code', GF_R_C_PLUGIN_SLUG); ?>
		<?php gform_tooltip('irma_qr'); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="checkbox" id="irma_qr_popup" class="irma_qr_popup" />
		<label for="irma_qr_popup" class="inline">
			<?php esc_html_e('Display as pop-up', GF_R_C_PLUGIN_SLUG); ?>
		</label>
	</div>
	<br>
	<label class="section_label" for="irma_qr_button_label">
		<?php esc_html_e('Button label', GF_R_C_PLUGIN_SLUG); ?>
		<?php gform_tooltip('irma_qr'); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="text" id="irma_qr_button_label" class="irma_qr_button_label fieldwidth-3" onchange="SetFieldProperty('irmaButtonLabel', this.value);" />
	</div>
</li>
