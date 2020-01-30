<?php

namespace Yard\IRMA;

class Attribute
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $rawValue;

    /**
     * @var array
     */
    private $value;

    /**
     * @var string
     */
    private $status;

    public function __construct(string $id, string $rawValue, array $value, string $status)
    {
        $this->id = $id;
        $this->rawValue = $rawValue;
        $this->value = $value;
        $this->status = $status;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->rawValue;
    }
}
