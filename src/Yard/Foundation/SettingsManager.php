<?php

namespace Yard\Foundation;

abstract class SettingsManager
{
    /**
    * Key of the option.
    *
    * @var string
    */
    protected $key = '';

    /**
    * Setting array of the option.
    */
    protected $settings;

    public function __construct($key = '')
    {
        if (! empty($key)) {
            $this->key     = $key;
        }
    }

    /**
     * Static constructor for quick setup.
     *
     * @return self
     */
    public static function make($key = ''): self
    {
        $class = get_called_class();
        return new $class($key);
    }

    public function save($data): bool
    {
        return update_option($this->key, $data);
    }

    public function all($default = [])
    {
        $all = get_option($this->key, $default);

        return $all;
    }

    public function get($key, $default = [])
    {
        $all = $this->all($default);

        return $all[$key] ?? $all;
    }
}
