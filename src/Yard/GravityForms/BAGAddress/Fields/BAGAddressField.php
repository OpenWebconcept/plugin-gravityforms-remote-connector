<?php

namespace Yard\GravityForms\BAGAddress\Fields;

use GF_Field;
use Yard\GravityForms\BAGAddress\Fields\Inputs\Text;

if (! class_exists('\GFForms')) {
    die();
}

class BAGAddressField extends GF_Field
{
    /**
     * @var string $type The field type.
     */
    public $type = 'bag-address';


    public function get_form_editor_field_settings()
    {
        return [
            // 'conditional_logic_field_setting',
            // 'prepopulate_field_setting',
            // 'error_message_setting',
            'label_setting',
            // 'admin_label_setting',
            // 'label_placement_setting',
            'sub_label_placement_setting',
            // 'default_input_values_setting',
            // 'input_placeholders_setting',
            // 'rules_setting',
            // 'copy_values_option',
            // 'description_setting',
            // 'visibility_setting',
            // 'css_class_setting',
        ];
    }

    public function is_conditional_logic_supported()
    {
        return true;
    }

    /**
     * Return the field title, for use in the form editor.
     *
     * @return string
     */
    public function get_form_editor_field_title()
    {
        return esc_attr__('BAG Address', GF_R_C_PLUGIN_SLUG);
    }

    public function validate($value, $form)
    {
    }

    public function get_field_input($form, $value = '', $entry = null)
    {
        $zip = (new Text($form, $this, $value))
            ->setFieldID(1)
            ->setFieldName('zip')
            ->setFieldText('Postcode')
            ->setFieldValue('6811 LV')
            ->setFieldPosition('left');

        $homeNumber = (new Text($form, $this, $value))
            ->setFieldID(2)
            ->setFieldName('home-number')
            ->setFieldText('Huisnummer')
            ->setFieldValue('55')
            ->setFieldPosition('left');

        $homeNumberAddition = (new Text($form, $this, $value))
            ->setFieldID(3)
            ->setFieldName('home-number-addition')
            ->setFieldText('Huisnummertoevoeging')
            ->setFieldValue('14')
            ->setFieldPosition('right');

        $city = (new Text($form, $this, $value))
            ->setFieldID(4)
            ->setFieldName('city')
            ->setFieldText('City')
            ->setReadonly()
            ->setFieldPosition('full');

        $address = (new Text($form, $this, $value))
            ->setFieldID(5)
            ->setFieldName('address')
            ->setFieldText('Address')
            ->setReadonly()
            ->setFieldPosition('left');

        $state = (new Text($form, $this, $value))
            ->setFieldID(6)
            ->setFieldName('state')
            ->setFieldText('State')
            ->setReadonly()
            ->setFieldPosition('full');

        $button = '<input type="button" id="bag-lookup" value="Opzoeken">';

        $result = '<div class="result"></div>';

        $inputs = $zip->render() . $homeNumber->render() . $homeNumberAddition->render() . $result . $button . $address->render() . $city->render() . $state->render();
        // $inputs                            = $zip->render() . $homeNumber->render();

        $is_admin                          = $this->is_entry_detail() || $this->is_form_editor();
        $form_id                           = absint($form['id']);
        $id                                = intval($this->id);
        $field_id                          = $this->is_entry_detail() || $this->is_form_editor() || 0 == $form_id ? "input_$id" : 'input_' . $form_id . "_$id";
        $form_id                           = ($is_admin) && empty($form_id) ? rgget('id') : $form_id;
        $disabled_text                     = $this->is_form_editor() ? "disabled='disabled'" : '';
        $class_suffix                      = $this->is_entry_detail() ? '_admin' : '';
        $required_attribute                = $this->isRequired ? 'aria-required="true"' : '';
        $form_sub_label_placement          = rgar($form, 'subLabelPlacement');
        $field_sub_label_placement         = $this->subLabelPlacement;
        $is_sub_label_above                = 'above' == $field_sub_label_placement || (empty($field_sub_label_placement) && 'above' == $form_sub_label_placement);
        $sub_label_class_attribute         = 'hidden_label' == $field_sub_label_placement ? "class='hidden_sub_label screen-reader-text'" : '';

        wp_register_script('bag_address-js', plugin_dir_url(GF_R_C_PLUGIN_FILE) . 'resources/js/bag-address.js');
        wp_enqueue_script('bag_address-js');
        wp_localize_script('bag_address-js', 'bag_address', ['ajaxurl' => admin_url('admin-ajax.php')]);

        return "<div class='ginput_complex{$this->class_suffix} ginput_container ginput_container_bag_address' id='{$field_id}'>
					{$inputs}
				<div class='gf_clear gf_clear_complex'></div>
			</div>";
    }

    // public function get_value_submission($field_values, $get_from_post_global_var = true)
    // {
    //     // dd(parent::get_value_submission($field_values, $get_from_post_global_var));
    //     return parent::get_value_submission($field_values, $get_from_post_global_var);
    // }

    public function get_value_entry_detail($value, $currency = '', $use_text = false, $format = 'html', $media = 'screen')
    {
        if (is_array($value) && ! empty($value)) {
            $zip               = trim($value[ $this->id . '.zip' ]);
            $homeNumber        = trim($value[ $this->id . '.home-number' ]);
            $quantity          = trim($value[ $this->id . '.3' ]);

            $product = $zip . ', ' . esc_html__('Qty: ', 'gravityforms') . $quantity . ', ' . esc_html__('Price: ', 'gravityforms') . $homeNumber;

            return $product;
        } else {
            return 'no values';
        }
    }
}
