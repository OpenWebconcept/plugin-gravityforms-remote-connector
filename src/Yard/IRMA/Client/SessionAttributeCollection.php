<?php

namespace Yard\Irma\Client;

use JsonSerializable;

final class SessionAttributeCollection implements JsonSerializable
{
    /**
     * @var string|null
     */
    private $label = null;

    /**
     * @var array
     */
    private $attributes = [];

    /**
	 * Static constructor
	 *
     * @return self
     */
    public static function make(): self
    {
        return new static;
    }

    /**
     * @param string $value
     * @return self
     */
    public function setLabel($value): self
    {
        $this->label = $value;

        return $this;
    }

    /**
     * @param string $value
     * @return self
     */
    public function add($value): self
    {
        $this->attributes[] = $value;

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'label'      => $this->label,
            'attributes' => $this->attributes
        ];
    }
}
