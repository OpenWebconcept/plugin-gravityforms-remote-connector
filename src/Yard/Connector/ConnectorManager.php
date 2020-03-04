<?php

namespace Yard\Connector;

use Yard\GravityForms\GravityFormsDefaultConnector;

final class ConnectorManager
{
    /**
     * All the available connectors.
     *
     * @var ConnectorEntity[]
     * @return self
     */
    private static $connectors = [];

    /**
     * Static constructor.
     *
     * @return self
     */
    public static function make(): self
    {
        return new static();
    }

    /**
     * Add connector to stack.
     *
     * @param ConnectorInterface $connector
     * @param string $name
     * @param string $identifier
     *
     * @return void
     */
    public static function add(ConnectorInterface $connector, string $identifier = '', string $name = ''): void
    {
        $entity = (new ConnectorEntity($connector))->build();
        if (!isset(static::$connectors[$entity->getIdentifier()])) {
            static::$connectors[$entity->getIdentifier()] = $entity;
        }
    }

    protected function getDefaultConnector(): ConnectorEntity
    {
        return (new ConnectorEntity(new GravityFormsDefaultConnector))->build();
    }

    /**
     * Find a given connector.
     *
     * @param string|array $identifier
     *
     * @return ConnectorEntity
     */
    public function find($identifier)
    {
        if (is_array($identifier)) {
            $identifier = $identifier['remote-connector-addon']['remote-connector-connectors'] ?? '';
        }

        $connector = array_filter($this->all(), function ($connector) use ($identifier) {
            return ($connector->getIdentifier() === $identifier);
        });

        if (empty($connector)) {
            return $this->getDefaultConnector();
        }

        return end($connector) ?? $connector;
    }

    /**
     * Get all connectors.
     *
     * @return ConnectorEntity[]
     */
    public function all(): array
    {
        array_map(function ($connector) {
            static::add(new $connector());
        }, \config('core.connectors', []));

        return static::$connectors;
    }

    /**
     * Register all connectors.
     */
    public function registerConnectors(): void
    {
        array_map(function ($identifier, $connector) {
            $connector->getConnector()->register();
        }, array_keys($this->all()), $this->all());
    }
}
