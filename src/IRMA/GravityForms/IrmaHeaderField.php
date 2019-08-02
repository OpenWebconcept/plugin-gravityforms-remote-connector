<?php

namespace IRMA\WP\GravityForms;

use IRMA\WP\GravityForms\IrmaField;

class IrmaHeaderField extends IrmaField
{
    public $type = 'IRMA-header';

    /**
     * Configure button for the form editor.
     *
     * @return array
     */
    public function get_form_editor_button()
    {
        return [
            'group' => 'advanced_fields',
            'text' => $this->get_form_editor_field_title(),
        ];
    }

    /**
     * Returns the field inner markup.
     *
     * @param array        $form
     * @param string|array $value
     * @param array|null   $entry
     *
     * @return string
     */
    public function get_field_input($form, $value = '', $entry = null)
    {
        $id = $this->id;

        $args = [
            'id' => $id,
            'formId' => $form['id'],
            'fieldId' => $this->is_entry_detail() || $this->is_form_editor() || $form['id'] == 0 ? "input_$id" : 'input_' . $form['id'] . "_$id",
            'placeholder' => $this->get_field_placeholder_attribute(),
            'text' => $this->text,
            'buttonLabel' => $this->buttonLabel,
            'popup' => !empty($this->irmaPopup) && $this->irmaPopup,

            // will be used as references to get value of referreds fields
            'irmaHeaderAttributeFullnameID' => 'input_' . $form['id'] . '_' . $this->irmaHeaderAttributeFullnameId,
            'irmaHeaderAttributeBsnID' => 'input_' . $form['id'] . '_' . $this->irmaHeaderAttributeBsnId,
            'logoUrl' => plugins_url('/resources/img/irma-logo-new.png', 'irma-wp/plugin.php'),
        ];

        $name = 'irma-header-input';
        return $this->renderView($name, $args);
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
            'irma_header',
            'visibility_setting',
            'error_message_setting',
            'placeholder_setting',
        ];
    }
}
