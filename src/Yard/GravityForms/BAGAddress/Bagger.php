<?php

namespace Yard\GravityForms\BAGAddress;

use GF_Field;
use Yard\GravityForms\BAGAddress\Inputs\Text;

if (! class_exists('\GFForms')) {
    die();
}

class Bagger extends GF_Field
{
    /**
     * @var string $type The field type.
     */
    public $type = 'bagger';

    /**
     * Return the field title, for use in the form editor.
     *
     * @return string
     */
    public function get_form_editor_field_title()
    {
        return esc_attr__('Attendees', 'gravityforms');
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
            'conditional_logic_field_setting',
            'prepopulate_field_setting',
            'error_message_setting',
            'label_setting',
            'admin_label_setting',
            'rules_setting',
            'duplicate_setting',
            'description_setting',
            'css_class_setting',
        ];
    }

    public function is_conditional_logic_supported()
    {
        return true;
    }

    public function get_field_input($form, $value = '', $entry = null)
    {
        $is_entry_detail         = $this->is_entry_detail();
        $is_form_editor          = $this->is_form_editor();

        $form_id               = $form['id'];
        $field_id              = intval($this->id);
        $disabled_text         = $is_form_editor ? "disabled='disabled'" : '';
        $class_suffix          = $is_entry_detail ? '_admin' : '';

        $required_attribute = $this->isRequired ? 'aria-required="true"' : '';
        $invalid_attribute  = $this->failed_validation ? 'aria-invalid="true"' : 'aria-invalid="false"';

        $zip = $homeNumber = $homeNumberAddition = $city = $address = $state = '';
        if (is_array($value)) {
            $zip                            = esc_attr(rgget($this->id . '.1', $value));
            $homeNumber                     = esc_attr(rgget($this->id . '.2', $value));
            $homeNumberAddition             = esc_attr(rgget($this->id . '.3', $value));
            $city                           = esc_attr(rgget($this->id . '.4', $value));
            $address                        = esc_attr(rgget($this->id . '.5', $value));
            $state                          = esc_attr(rgget($this->id . '.6', $value));
        }

        $zip_markup = (new Text($form, $this, $zip))
            ->setFieldID(1)
            ->setFieldName('zip')
            ->setFieldText('Postcode')
            ->setFieldValue('6811 LV')
            ->setFieldPosition('left')
            ->render();

        $homeNumber_markup = (new Text($form, $this, $homeNumber))
            ->setFieldID(2)
            ->setFieldName('homeNumber')
            ->setFieldText('Huisnummer')
            ->setFieldValue('55')
            ->setFieldPosition('left')
            ->render();

        $homeNumberAddition_markup = (new Text($form, $this, $homeNumberAddition))
            ->setFieldID(3)
            ->setFieldName('homeNumberAddition')
            ->setFieldText('Huisnummertoevoeging')
            ->setFieldValue('14')
            ->setFieldPosition('right')
            ->render();

        $city_markup = (new Text($form, $this, $city))
            ->setFieldID(4)
            ->setFieldName('city')
            ->setFieldText('City')
            ->setReadonly()
            ->setFieldPosition('full')
            ->render();

        $address_markup = (new Text($form, $this, $address))
            ->setFieldID(5)
            ->setFieldName('address')
            ->setFieldText('Address')
            ->setReadonly()
            ->setFieldPosition('left')
            ->render();

        $state_markup = (new Text($form, $this, $state))
            ->setFieldID(6)
            ->setFieldName('state')
            ->setFieldText('State')
            ->setReadonly()
            ->setFieldPosition('full')
            ->render();

        $button = '<input type="button" id="bag-lookup" value="Opzoeken">';

        $result = '<div class="result"></div>';

        wp_register_script('bag_address-js', plugin_dir_url(GF_R_C_PLUGIN_FILE) . 'resources/js/bag-address.js');
        wp_enqueue_script('bag_address-js');
        wp_localize_script('bag_address-js', 'bag_address', ['ajaxurl' => admin_url('admin-ajax.php')]);

        $class_suffix  = $this->is_entry_detail() ? '_admin' : '';

        return "<div class='ginput_complex{$class_suffix} ginput_container gfield_trigger_change' id='{$field_id}'>
                    {$zip_markup}
                    {$homeNumber_markup}
                    {$homeNumberAddition_markup}
                    {$button}
                    {$result}
                    {$city_markup}
                    {$address_markup}
                    {$state_markup}
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
            $zip                      = trim(rgget($this->id . '.1', $value));
            $homeNumber               = trim(rgget($this->id . '.2', $value));
            $homeNumberAddition       = trim(rgget($this->id . '.3', $value));
            $city                     = trim(rgget($this->id . '.4', $value));
            $address                  = trim(rgget($this->id . '.5', $value));
            $state                    = trim(rgget($this->id . '.6', $value));

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
