<?php

namespace Yard\IRMA\GravityForms\Fields;

class IrmaLaunchQR extends IrmaField
{
    /**
     * @var string
     */
    public $type = 'IRMA-launch-QR';

    /**
     * Renders the field.
     *
     * @param array $form
     * @param string $value
     * @param object $entry
     *
     * @return string
     */
    public function get_field_input($form, $value = '', $entry = null): string
    {
        $formId = $form['id'];
        $id     = (int) $this->id;

        if ($this->is_form_editor()) {
            return '<img src="' . plugins_url('resources/img/qr_code.jpg', GF_R_C_ROOT_PATH .'/'. GF_R_C_PLUGIN_FILE) . '"/>';
        }

        $args = [
            'id'			     => $id,
            'formId'		    => $formId,
            'buttonLabel'	=> empty($this->irmaButtonLabel) ? __('Get IRMA attributes', GF_R_C_PLUGIN_SLUG) : $this->irmaButtonLabel,
            'popup' 		    => !empty($this->irmaPopup) && $this->irmaPopup,
        ];

        $name = 'qr-launch-input';
        return $this->renderView($name, $args);
    }

    /**
     * Validate form to ensure that we have a session token.
     *
     * @param string $value
     * @param array $form
     * @return void
     */
    public function validate($value, $form): void
    {
        $sessionToken = $this->get_input_value_submission('input_' . $form['id'] . '_irma_session_token');

        if (empty($sessionToken)) {
            $this->failed_validation  = true;
            $this->validation_message = empty($this->errorMessage) ? __('Please fetch your IRMA attributes.', GF_R_C_PLUGIN_SLUG) : $this->errorMessage;
            return;
        }
    }

    /**
     * Defines how to display the field in the form editor.
     *
     * @return array
     */
    public function get_form_editor_button(): array
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
