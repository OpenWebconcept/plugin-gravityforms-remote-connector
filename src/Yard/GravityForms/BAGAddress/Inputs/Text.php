<?php

namespace Yard\GravityForms\BAGAddress\Inputs;

class Text extends AbstractInput
{

    /** @var int ID of the field in the form. */
    protected $fieldID = null;

    /** @var string Position of the field in the form. */
    protected $fieldPosition = 'full';

    /** @var string Field identifier. */
    protected $fieldName;

    /** @var string Field text. */
    protected $fieldText = '';

    /** @var bool Set input to readonly. */
    protected $readonly = false;

    /**
     * Set the value of fieldPosition
     *
     * @return  self
     */
    public function setFieldPosition($fieldPosition)
    {
        $this->fieldPosition = $fieldPosition;

        return $this;
    }

    /**
     * Set the value of fieldID
     *
     * @return  self
     */
    public function setFieldID($fieldID)
    {
        $this->fieldID = $fieldID;

        return $this;
    }


    /**
     * Set the value of fieldText
     *
     * @return  self
     */
    public function setFieldText($fieldText)
    {
        $this->fieldText = $fieldText;

        return $this;
    }


    /**
     * Set the value of fieldName
     *
     * @return  self
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Set the value to readonly.
     *
     * @return self
     */
    public function setReadonly()
    {
        $this->readonly = 'readonly';

        return $this;
    }

    /**
     * Set the value of field.
     *
     * @return  self
     */
    public function setFieldValue($value)
    {
        $this->value = $value;

        return $this;
    }
}
