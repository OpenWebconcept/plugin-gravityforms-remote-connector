<?php

namespace IRMA\WP\GravityForms;

use GF_Field;
use IRMA\WP\Foundation\Plugin;

class IrmaLaunchQR extends GF_Field
{
	/**
	 * @var string
	 */
	public $type = 'IRMA-launch-QR';

	/**
	 * The class names of the settings which should be available on the field in the form editor.
	 *
	 * @return array
	 */
	public function get_form_editor_field_settings()
	{

		return array(
			// 'conditional_logic_field_setting',
			// 'prepopulate_field_setting',
			'error_message_setting',
			'label_setting',
			// 'admin_label_setting',
			// 'rules_setting',
			// 'visibility_setting',
			// 'description_setting',
			// 'css_class_setting',
			// 'select_all_choices_setting',
		);
	}

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
		$form_id = $form['id'];
		$id = (int)$this->id;

		if ($this->is_form_editor()) {
			return '<img src="' . plugins_url('resources/img/qr_code.jpg', 'irma-wp/plugin.php') . '"/>';
		}

		$input = '<div class="ginput_container ginput_container_irma_qr" id="gf_irma_container_' . $id . '">' .
			'<input type="button" value="Haal IRMA attributen op" ' . $this->get_tabindex() . ' class="btn btn-secondary gf_irma_qr" data-id="' . $id . '" data-form-id="' . $form_id . '">' .
			'<canvas id="gf_irma_qr_' . $id . '" class="gf_irma_qr_canvas"></canvas>' .
			'<input type="hidden" name="input_' . $form_id . '_irma_session_token" id="input_' . $form_id . '_irma_session_token" />' .
			"</div>";

		return $input;
	}

	public function validate($value, $form)
	{
		$sessionToken = $this->get_input_value_submission('input_' . $form['id'] . '_irma_session_token');

		if (empty($sessionToken)) {
			$this->failed_validation = true;
			$this->validation_message = empty($this->errorMessage) ? 'Please fetch your IRMA attributes.' : $this->errorMessage;
			return;
		}
	}

	public function get_form_editor_button()
	{
		return [
			'group' => 'advanced_fields',
			'text'  => $this->get_form_editor_field_title(),
		];
	}
}
