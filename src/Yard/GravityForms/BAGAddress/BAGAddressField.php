<?php

namespace Yard\GravityForms\BAGAddress;

use GF_Field;
use Yard\GravityForms\BAGAddress\Inputs\StringInput;
use Yard\GravityForms\BAGAddress\Inputs\TextInput;

if (! class_exists('\GFForms')) {
    die();
}

class BAGAddressField extends GF_Field
{
    /**
     * @var string $type The field type.
     */
    public $type = 'bag_address';

    /**
     * Return the field title, for use in the form editor.
     *
     * @return string
     */
    public function get_form_editor_field_title()
    {
        return esc_attr__('BAG Address', GF_R_C_PLUGIN_SLUG);
    }

    public function get_form_editor_button()
    {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function get_form_editor_field_settings()
    {
        return [
            'sub_label_placement_setting',
            'input_placeholders_setting',
            'rules_setting',
            'conditional_logic_field_setting',
            'label_setting',
            'rules_setting',
            'description_setting',
            'css_class_setting',
        ];
    }

    /**
     * This field type can be used when configuring conditional logic rules.
     *
     * @return bool
     */
    public function is_conditional_logic_supported(): bool
    {
        return true;
    }

    /**
     * Override this method to perform custom validation logic.
     *
     * Return the result (bool) by setting $this->failed_validation.
     * Return the validation message (string) by setting $this->validation_message.
     *
     * @param string|array $value The field value from get_value_submission().
     * @param array        $form  The Form Object currently being processed.
     */
    public function validate($value, $form)
    {
        if ($this->isRequired) {
            $zip                  = rgar($value, $this->id . '.1');
            $homeNumber           = rgar($value, $this->id . '.2');

            if (empty($zip) && empty($homeNumber)) {
                $this->failed_validation  = true;
                $this->validation_message = empty($this->errorMessage) ? esc_html__('This field is required. Please enter a complete address.', 'gravityforms') : $this->errorMessage;
            }
        }

        $city                     = rgar($value, $this->id . '.4');
        $address                  = rgar($value, $this->id . '.5');
        $state                    = rgar($value, $this->id . '.6');
        if (empty($city) && empty($address) && empty($state)) {
            $this->failed_validation  = true;
            $this->validation_message = empty($this->errorMessage) ? esc_html__('This field is required. Please enter a complete address.', 'gravityforms') : $this->errorMessage;
        }
    }

    protected function getFields($value): array
    {
        $form = \GFAPI::get_form($this->formId);
        $zip  = (new TextInput($form, $this, $value))
                ->setFieldID(1)
                ->setFieldName('zip')
                ->setFieldText('Postcode')
                ->setFieldValue('6811 LV')
                ->setFieldPosition('left');

        $homeNumber = (new TextInput($form, $this, $value))
                ->setFieldID(2)
                ->setFieldName('homeNumber')
                ->setFieldText('Huisnummer')
                ->setFieldValue('55')
                ->setFieldPosition('left');

        $homeNumberAddition = (new TextInput($form, $this, $value))
                ->setFieldID(3)
                ->setFieldName('homeNumberAddition')
                ->setFieldText('Huisnummertoevoeging')
                ->setFieldValue('14')
                ->setFieldPosition('right');

        $button = (new StringInput())
            ->setContent('<input type="button" id="bag-lookup" value="Opzoeken">');

        $result = (new StringInput())
            ->setContent('<div class="result"></div>');

        $city = (new TextInput($form, $this, $value))
                ->setFieldID(4)
                ->setFieldName('city')
                ->setFieldText('City')
                ->setReadonly()
                ->setFieldPosition('full');

        $address = (new TextInput($form, $this, $value))
                ->setFieldID(5)
                ->setFieldName('address')
                ->setFieldText('Address')
                ->setReadonly()
                ->setFieldPosition('left');

        $state = (new TextInput($form, $this, $value))
                ->setFieldID(6)
                ->setFieldName('state')
                ->setFieldText('State')
                ->setReadonly()
                ->setFieldPosition('full');

        return [
            $zip,
            $homeNumber,
            $homeNumberAddition,
            $button,
            $result,
            $city,
            $address,
            $state
        ];
    }

    public function get_field_input($form, $value = '', $entry = null)
    {
        wp_register_script('bag_address-js', plugin_dir_url(GF_R_C_PLUGIN_FILE) . 'resources/js/bag-address.js');
        wp_enqueue_script('bag_address-js');
        wp_localize_script('bag_address-js', 'bag_address', ['ajaxurl' => admin_url('admin-ajax.php')]);

        $output = implode(' ', array_map(function ($item) {
            return $item->render();
        }, $this->getFields($value)));

        return "<div class='ginput_complex{$this->class_suffix} ginput_container ginput_container_bag_address' id='input_{$form['id']}_{intval($this->id)}'>
                    {$output}
                <div class='gf_clear gf_clear_complex'></div>
            </div>";
    }

    public function get_form_editor_inline_script_on_page_render()
    {

        // set the default field label for the field
        $script = sprintf("function SetDefaultValues_%s(field) {
        field.label = '%s';
        field.inputs = [
			new Input(field.id + '.1', '%s'),
			new Input(field.id + '.2', '%s'),
			new Input(field.id + '.3', '%s'),
			new Input(field.id + '.4', '%s'),
			new Input(field.id + '.5', '%s'),
			new Input(field.id + '.6', '%s')
		];
        }", $this->type, $this->get_form_editor_field_title(), 'Zip', 'HomeNumber', 'HomeNumberAddition', 'City', 'Address', 'State') . PHP_EOL;

        return $script;
    }

    public function get_value_entry_detail($value, $currency = '', $use_text = false, $format = 'html', $media = 'screen')
    {
        if (is_array($value)) {
            $zip                         = trim(rgget($this->id . '.1', $value));
            $homeNumber                  = trim(rgget($this->id . '.2', $value));
            $homeNumberAddition          = trim(rgget($this->id . '.3', $value));
            $city                        = trim(rgget($this->id . '.4', $value));
            $address                     = trim(rgget($this->id . '.5', $value));
            $state                       = trim(rgget($this->id . '.6', $value));

            $return = $zip;
            $return .= ! empty($return) && ! empty($homeNumber) ? " $homeNumber" : $homeNumber;
            $return .= ! empty($return) && ! empty($homeNumberAddition) ? "$homeNumberAddition" : $homeNumberAddition;
            $return .= ! empty($return) && ! empty($city) ? " $city" : $city;
            $return .= ! empty($return) && ! empty($address) ? " $address" : $address;
            $return .= ! empty($return) && ! empty($state) ? " $state" : $state;
        } else {
            $return = '';
        }

        if ('html' === $format) {
            $return = esc_html($return);
        }

        return $return;
    }
}
