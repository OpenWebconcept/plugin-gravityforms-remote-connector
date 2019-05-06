<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaAttributeField extends GF_Field
{
	public $type = 'IRMA-attribute';

	public function get_form_editor_button()
	{
		return [
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		];
	}

	/**
	 * Returns the field inner markup.
	 *
	 * @param array        $form
	 * @param string|array $value
	 * @param null|array   $entry
	 *
	 * @return string
	 */
	public function get_field_input($form, $value = '', $entry = null)
	{
		$form_id         = absint($form['id']);
		$is_entry_detail = $this->is_entry_detail();
		$is_form_editor  = $this->is_form_editor();

		$id            = $this->id;
		$field_id      = $is_entry_detail || $is_form_editor || $form_id == 0 ? "input_$id" : 'input_' . $form_id . "_$id";

		return '<div class="ginput_container ginput_container_irma_attribute">
			<input type="text" id="' . esc_attr($field_id) . '" name="input_' . $id . '" readonly/>
			</div>';
	}

	public function validate($value, $form)
	{
		$this->failed_validation = false;
	}

	public function get_form_editor_field_settings()
	{
		return [
			'label_setting',
			'rules_setting',
			'irma_attribute_list',
			'visibility_setting',
		];
	}
}
