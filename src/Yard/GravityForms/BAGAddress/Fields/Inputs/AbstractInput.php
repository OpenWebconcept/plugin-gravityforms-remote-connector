<?php

namespace Yard\GravityForms\BAGAddress\Fields\Inputs;

use GFCommon;
use GFFormsModel;

abstract class AbstractInput
{
    /** @var array form object */
    protected $form;

    /** @var stdClass */
    protected $field;

    /** @var string Set default value of input. */
    protected $value;

    public function __construct(array $form, $field, $value)
    {
        // dd($form, $field, $value);
        $this->form                                                             = $form;
        $this->formID                                                           = absint($this->form['id']);
        $this->field                                                            = $field;
        $this->id                                                               = intval($this->field->id);
        $this->is_entry_detail                                                  = $this->field->is_entry_detail();
        $this->is_form_editor                                                   = $this->field->is_form_editor();
        $this->field_id                                                         = $this->is_entry_detail || $this->is_form_editor || 0 == $this->formID ? "input_$this->id" : 'input_' . $this->formID . "_$this->id";
        $this->form_id                                                          = ($this->is_entry_detail || $this->is_form_editor) && empty($this->formID) ? rgget('id') : $this->formID;
        $this->value                                                            = $value;
        $this->tabindex                                                         = $this->field->get_tabindex();
        $this->is_admin                                                         = $this->is_entry_detail || $this->is_form_editor;
        $this->style                                                            = ($this->is_admin && rgar($this->getInput(), 'isHidden')) ? "style='display:none;'" : '';
        $this->disabled_text                                                    = $this->is_form_editor ? "disabled='disabled'" : '';
        $this->class_suffix                                                     = $this->is_entry_detail ? '_admin' : '';
        $this->required_attribute                                               = $this->field->isRequired ? 'aria-required="true"' : '';
        $this->form_sub_label_placement                                         = rgar($this->form, 'subLabelPlacement');
        $this->field_sub_label_placement                                        = $this->field->subLabelPlacement;
        $this->is_sub_label_above                                               = 'above' == $this->field_sub_label_placement || (empty($this->field_sub_label_placement) && 'above' == $this->form_sub_label_placement);
        $this->sub_label_class_attribute                                        = 'hidden_label' == $this->field_sub_label_placement ? "class='hidden_sub_label screen-reader-text'" : '';
        $this->is_field_hidden                                                  = false;
    }

    /**
     * Get the submitted value.
     *
     * @return string|array
     */
    public function getValue()
    {
        $value  = '';
        if (is_array($this->value)) {
            $value  = esc_attr(rgpost($this->id . '.'. $this->fieldID, $this->value));
        } elseif (!empty($this->value)) {
            $value = $this->value;
        }

        return $value;
    }

    /**
     * Return the input object.
     *
     * @return void
     */
    public function getInput()
    {
        return GFFormsModel::get_input($this->field, $this->field->id . '.'. $this->fieldID);
    }

    public function getPlaceholder()
    {
        return GFCommon::get_input_placeholder_attribute($this->getInput());
    }

    public function getLabel()
    {
        return '' != rgar($this->getInput(), 'customLabel') ? $this->getInput()['customLabel'] : $this->fieldText;
    }

    public function render()
    {
        if ($this->is_admin || ! rgar($this->getInput(), 'isHidden')) {
            if ($this->is_sub_label_above) {
                return "<span class='ginput_{$this->fieldPosition}{$this->class_suffix} {$this->fieldName}' id='{$this->field_id}_{$this->fieldID}_container' {$this->style}>
						<label for='{$this->field_id}_{$this->fieldID}' id='{$this->field_id}_{$this->fieldID}_label' {$this->sub_label_class_attribute}>{$this->getLabel()}</label>
						<input
							type='text'
							data-name='{$this->fieldName}'
							name='input_{$this->id}.{$this->fieldID}'
							id='{$this->field_id}_{$this->fieldID}'
							value='{$this->getValue()}'
							{$this->tabindex} {$this->disabled_text} {$this->readonly} {$this->getPlaceholder()} {$this->required_attribute}
						/>
					</span>";
            } else {
                return "<span class='ginput_{$this->fieldPosition}{$this->class_suffix} {$this->fieldName}' id='{$this->field_id}_{$this->fieldID}_container' {$this->style}>
						<input
							type='text'
							data-name='{$this->fieldName}'
							name='input_{$this->id}.{$this->fieldID}'
							id='{$this->field_id}_{$this->fieldID}'
							value='{$this->getValue()}'
							{$this->tabindex} {$this->disabled_text} {$this->readonly} {$this->getPlaceholder()} {$this->required_attribute}
						/>
						<label for='{$this->field_id}_{$this->fieldID}' id='{$this->field_id}_{$this->fieldID}_label' {$this->sub_label_class_attribute}>{$this->getLabel()}</label>
					</span>";
            }
        }
    }
}
