<?php

namespace IRMA\WP\GravityForms;

use GF_Field;

class IrmaHeaderField extends GF_Field
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
        $formId = absint($form['id']);
        $fieldId = $this->is_entry_detail() || $this->is_form_editor() || $formId == 0 ? "input_$id" : 'input_'.$formId."_$id";
        $placeholder = $this->get_field_placeholder_attribute();
        $value = $this->irmaHeader;
        // $value = is_array($value) ? rgar($value, 0) : $value;
        // $value = esc_attr($value);

        ob_start();
        $logoUrl = plugins_url('/resources/img/irma_icon.png', 'irma-wp/plugin.php');
        require __DIR__.'/resources/irma-header-input.php';

        return ob_get_clean();
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
            'rules_setting',
            'irma_header',
            'visibility_setting',
            'error_message_setting',
            'placeholder_setting',
        ];
    }
}
