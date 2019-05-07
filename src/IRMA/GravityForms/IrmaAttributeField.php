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

		$value = is_array($value) ? rgar($value, 0) : $value;
		$value = esc_attr($value);

		return '<div class="ginput_container ginput_container_irma_attribute">
			<input type="text" id="' . esc_attr($field_id) . '" name="input_' . $id . '" value="' . $value . '"/>
			</div>';
	}

	public function validate($value, $form)
	{
		$sessionToken = $this->get_input_value_submission('input_' . $form['id'] . '_irma_session_token');
		$groundTruth = get_transient('irma_result_' . $sessionToken);

		if (empty($sessionToken) || !$groundTruth) {
			$this->failed_validation = true;
			$this->validation_message = 'Please fetch this attribute from IRMA.';
			return;
		}

		foreach ($groundTruth as $item) {
			if ($item['input'] == 'input_' . $form['id'] . '_' . $this->id) {
				if ($value !== $item['value']) {
					$this->failed_validation = true;
				}
				break;
			}
		}
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
