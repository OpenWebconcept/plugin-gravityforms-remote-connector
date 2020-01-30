<?php

namespace Yard\IRMA\GravityForms\Fields;

use Yard\IRMA\Settings\SettingsManager;

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
            'text'  => $this->get_form_editor_field_title(),
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
        $value = is_array($value) ? rgar($value, 0) : $value;
        $value = esc_attr($value);
        $id    = $this->id;
        $link  = (isset($_SERVER['HTTPS']) && 'on' === $_SERVER['HTTPS'] ?
            'https' : 'http').'://'.$_SERVER['HTTP_HOST'].
            $_SERVER['REQUEST_URI'];

        $args = [
            'id'          => $id,
            'formId'      => $form['id'],
            'fieldId'     => $this->is_entry_detail() || $this->is_form_editor() || 0 == $form['id'] ? "input_$id" : 'input_'.$form['id']."_$id",
            'placeholder' => $this->get_field_placeholder_attribute(),
            'value'       => $value,
            'text'        => $this->text,
            'buttonLabel' => $this->buttonLabel,
            'popup'       => !empty($this->irmaPopup) && $this->irmaPopup,
            'link'        => $link,

            // will be used as references to get value of referreds fields
            'irmaHeaderAttributeFullnameID' => $this->irmaHeaderAttributeFullnameId,
            'irmaHeaderAttributeBsnID'      => $this->irmaHeaderAttributeBsnId,
            'irmaHeaderAttributeCity'       => $this->irmaHeaderAttributeCity,
            'irmaHeaderCity'                => $this->irmaHeaderCity,
            'logoUrl'                       => plugins_url('/resources/img/irma-logo-new.png', 'irma-wp/plugin.php'),
        ];

        add_action('gform_enqueue_scripts', function () use ($args) {
            wp_register_script('irma-header-js', false);
            wp_localize_script('irma-header-js', 'irma_header', $args);
            wp_enqueue_script('irma-header-js');
        });

        $name = 'irma-header-input';

        return $this->renderView($name, $args);
    }

    public function validate($value, $form)
    {
        // Retrieve the session token from the form data.
        $sessionToken = $this->get_input_value_submission('input_'.$form['id'].'_irma_session_token');
        $groundTruth  = get_transient('irma_result_'.$sessionToken);

        if (empty($sessionToken) || !$groundTruth) {
            $this->failed_validation  = true;
            $this->validation_message = '';

            return;
        }

        $settingsManager = new SettingsManager();

        // Compare submitted value with the true value.
        foreach ($groundTruth as $item) {
            if ('input_'.$form['id'].'_'.$this->id == $item['input'] && $settingsManager->getAttributeBSN() == $item['attribute']) {
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
            'irma_header',
            'visibility_setting',
            'error_message_setting',
            'placeholder_setting',
            'conditional_logic_field_setting',
            'prepopulate_field_setting',
        ];
    }
}
