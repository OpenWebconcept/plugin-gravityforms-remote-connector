<?php

namespace Yard\Foundation;

abstract class SettingsManager
{
    public function __construct()
    {
        $this->settings = get_option($this->key, []);
    }

    /**
     * Static constructor for quick setup.
     *
     * @return self
     */
    public static function make(): self
    {
        $class = get_called_class();
        return new $class();
    }

    public function save($data): bool
    {
        return update_option($this->key, $data);
    }

    public function all($default = [])
    {
        $all = get_option($this->key, $default);
        if (!isset($all['attributes'])) {
            $all['attributes'] = [];
        }

        return $all;
    }

    public function get($key, $default = [])
    {
        $all = $this->all($default);

        return $all[$key] ?? $all;
    }
}
