<?php

namespace Yard\IRMA\GravityForms;

use Yard\IRMA\GravityForms\IrmaField;

class IrmaAttributeField extends IrmaField
{
	public $type = 'IRMA-attribute';

	/**
	 * Configure button for the form editor.
	 *
	 * @return array
	 */
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
		$value = is_array($value) ? rgar($value, 0) : $value;
		$value = esc_attr($value);
		$id = $this->id;

		$args = [
			'id'            => $id,
			'formId'        => absint($form['id']),
			'fieldId'      	=> $this->is_entry_detail() || $this->is_form_editor() || $form['id'] == 0 ? "input_$id" : 'input_' . $form['id'] . "_$id",
			'placeholder'	=> $this->get_field_placeholder_attribute(),
			'value'			=> $value,
		];

		$name = 'irma-attribute-input';
		return $this->renderView($name, $args);
	}

	/**
	 * Validates the IRMA Attribute field.
	 *
	 * @param string $value
	 * @param array $form
	 * @return void
	 */
	public function validate($value, $form)
	{
		// Retrieve the session token from the form data.
		$sessionToken = $this->get_input_value_submission('input_' . $form['id'] . '_irma_session_token');
		$groundTruth = get_transient('irma_result_' . $sessionToken);

		if (empty($sessionToken) || !$groundTruth) {
			$this->failed_validation = true;
			$this->validation_message = empty($this->errorMessage) ? 'Please fetch this attribute from IRMA.' : $this->errorMessage;
			return;
		}

		// Compare submitted value with the true value.
		foreach ($groundTruth as $item) {
			if ($item['input'] == 'input_' . $form['id'] . '_' . $this->id) {
				if ($value !== $item['value']) {
					$this->failed_validation = true;
				}
				break;
			}
		}
	}

	public function is_conditional_logic_supported()
	{
		return true;
	}

	/**
	 * Settings which are available to the form field.
	 *
	 * @return array
	 */
	public function get_form_editor_field_settings()
	{
		return [
			'label_setting',
			'label_placement_setting',
			'description_setting',
			'rules_setting',
			'irma_attribute_list',
			'visibility_setting',
			'error_message_setting',
			'placeholder_setting',
			'conditional_logic_field_setting',
		];
	}
}
