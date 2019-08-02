<li class="irma_attribute field_setting">
	<label class="section_label" for="irma_attribute_list_label">
		<?php esc_html_e('IRMA Attribute', 'irma-wp'); ?>
		<?php gform_tooltip('irma_attributes'); ?>
	</label>
	<div class="irma_attribute_list">
		<input type="text" class="irma_attribute_field" onchange="SetFieldProperty('irmaAttribute', this.value);" />
	</div>
</li>

<li class="irma_header field_setting">
	<label class="section_label" for="irma_header_list_label">
		<?php esc_html_e('IRMA Header text', 'irma-wp'); ?>
	</label>
	<div class="irma_header_list">
		<input type="text" class="irma_header_field" onchange="SetFieldProperty('text', this.value);" />
	</div>
	<br />
	<div class="irma_qr_popup_container">
		<label class="section_label" for="irma_header_qr_popup">
			<?php esc_html_e('QR Code', 'irma-wp'); ?>
		</label>
		<input type="checkbox" id="irma_header_qr_popup" class="irma_header_qr_popup" />
		<label for="irma_header_qr_popup" class="inline">
			<?php esc_html_e('Display as pop-up', 'irma-wp'); ?>
		</label>
	</div>
	<br />
	<label class="section_label" for="irma_qr_button_label">
		<?php esc_html_e('Button label', 'irma-wp'); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="text" id="irma_header_button_label" class="irma_header_button_label" onchange="SetFieldProperty('buttonLabel', this.value);" />
	</div>
	<br />
	<label class="section_label" for="irma_header_attribute_fullname_id_label">
		<?php esc_html_e('ID attribute fullname', 'irma-wp'); ?>
		<?php gform_tooltip('irma_header_attribute_fullname_id'); ?>
	</label>
	<div class="irma_header_attribute_fullname_id">
		<input type="text" class="irma_header_attribute_fullname_id" onchange="SetFieldProperty('irmaHeaderAttributeFullnameId', this.value);" />
	</div>
	<br />
	<label class="section_label" for="irma_header_attribute_bns_id_label">
		<?php esc_html_e('ID attribute BSN', 'irma-wp'); ?>
		<?php gform_tooltip('irma_header_attribute_bsn_id'); ?>
	</label>
	<div class="irma_header_attribute_bsn_id">
		<input type="text" class="irma_header_attribute_bsn_id" onchange="SetFieldProperty('irmaHeaderAttributeBsnId', this.value);" />
	</div>
</li>

<li class="irma_qr field_setting">
	<label class="section_label" for="irma_qr_popup">
		<?php esc_html_e('QR Code', 'irma-wp'); ?>
		<?php gform_tooltip('irma_qr'); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="checkbox" id="irma_qr_popup" class="irma_qr_popup" />
		<label for="irma_qr_popup" class="inline">
			<?php esc_html_e('Display as pop-up', 'irma-wp'); ?>
		</label>
	</div>
	<br>
	<label class="section_label" for="irma_qr_button_label">
		<?php esc_html_e('Button label', 'irma-wp'); ?>
		<?php gform_tooltip('irma_qr'); ?>
	</label>
	<div class="irma_qr_popup_container">
		<input type="text" id="irma_qr_button_label" class="irma_qr_button_label" onchange="SetFieldProperty('irmaButtonLabel', this.value);" />
	</div>
</li>