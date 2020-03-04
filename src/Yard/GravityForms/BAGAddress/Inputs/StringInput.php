<?php

namespace Yard\GravityForms\BAGAddress\Inputs;

class StringInput
{
    protected $content;

    public function render(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
