<?php

namespace Yard\Connector;

use ReflectionClass;

class ConnectorEntity
{
    /**
     * Connector
     *
     * @var ConnectorInterface
     */
    protected $connector;

    /**
     * Name of the connector.
     *
     * @var string
     */
    protected $name = '';

    /**
     * Identifier of teh connector.
     *
     * @var string
     */
    protected $identifier = '';

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector  = $connector;
    }

    /**
     * Build the connectorEntity.
     *
     * @return self
     */
    public function build(): self
    {
        if (empty($identifier)) {
            $identifier = (new ReflectionClass(get_class($this->connector)))->getShortName();
        }
        $this->setIdentifier(strtolower($identifier));

        if (empty($name)) {
            $name = (new ReflectionClass(get_class($this->connector)))->getShortName();
        }

        $this->setName($name);

        return $this;
    }

    /**
     * Get the value of name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @var string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of identifier
     *
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * Set the value of identifier
     *
     * @var string $identifier
     * @return self
     */
    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Get the value of connector
     *
     * @return ConnectorInterface
     */
    public function getConnector(): ConnectorInterface
    {
        return $this->connector;
    }
}
