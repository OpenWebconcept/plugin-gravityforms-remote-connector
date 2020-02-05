<?php

namespace Yard\Connector;

use ReflectionClass;

class ConnectorEntity
{
    protected $connector;

    protected $name = '';

    protected $identifier = '';

    public function __construct(ConnectorInterface $connector)
    {
        $this->connector  = $connector;
    }

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
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set the value of identifier
     *
     * @return  self
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * Get the value of connector
     */
    public function getConnector()
    {
        return $this->connector;
    }
}
