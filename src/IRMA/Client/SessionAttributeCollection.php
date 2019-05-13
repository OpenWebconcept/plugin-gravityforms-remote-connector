<?php

namespace IRMA\WP\Client;

use JsonSerializable;

class SessionAttributeCollection implements JsonSerializable
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
	 * @return AttributeCollection
	 */
	public static function make()
	{
		return new static;
	}

	/**
	 * @param string $value
	 * @return AttributeCollection
	 */
	public function setLabel($value)
	{
		$this->label = $value;

		return $this;
	}

	/**
	 * @param string $value
	 * @return AttributeCollection
	 */
	public function add($value)
	{
		$this->attributes[] = $value;

		return $this;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize()
	{
		return [
			'label' => $this->label,
			'attributes' => $this->attributes
		];
	}
}
