<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaLaunchQR extends GF_Field
{
	/**
	 * @var string
	 */
	public $type = 'IRMA-launch-QR';

	/**
	 * Renders the field.
	 *
	 * @param string $form
	 * @param string $value
	 * @param object $entry
	 *
	 * @return string
	 */
	public function get_field_input($form, $value = '', $entry = null)
	{
		$formId = $form['id'];
		$id = (int) $this->id;

		if ($this->is_form_editor()) {
			return '<img src="' . plugins_url('resources/img/qr_code.jpg', 'irma-wp/plugin.php') . '"/>';
		}

		$buttonLabel = empty($this->irmaButtonLabel) ? __('Get IRMA attributes', 'irma-wp') : $this->irmaButtonLabel;
		$popup = !empty($this->irmaPopup) && $this->irmaPopup;

		ob_start();
		require __DIR__ . '/resources/qr-launch-input.php';

		return ob_get_clean();
	}

	/**
	 * Validate form to ensure that we have a session token.
	 *
	 * @param string $value
	 * @param array $form
	 * @return void
	 */
	public function validate($value, $form)
	{
		$sessionToken = $this->get_input_value_submission('input_' . $form['id'] . '_irma_session_token');

		if (empty($sessionToken)) {
			$this->failed_validation = true;
			$this->validation_message = empty($this->errorMessage) ? __('Please fetch your IRMA attributes.', 'irma-wp') : $this->errorMessage;
			return;
		}
	}

	/**
	 * Defines how to display the field in the form editor.
	 *
	 * @return void
	 */
	public function get_form_editor_button()
	{
		return [
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		];
	}

	/**
	 * The class names of the settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	public function get_form_editor_field_settings()
	{
		return [
			'error_message_setting',
			'irma_qr',
			'label_setting',
			'label_placement_setting',
			'description_setting',
		];
	}
}
