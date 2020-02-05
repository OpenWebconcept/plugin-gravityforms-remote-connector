<?php


if (!function_exists('config')) {
    function config($setting, $default = '')
    {
        $config   = new \Yard\Foundation\Config(GF_R_C_ROOT_PATH.'/config');
        $config->boot();
        return $config->get($setting, $default);
    }
}

if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed  $args
     * @return void
     */
    function dd(...$args)
    {
        echo '<pre>';
        foreach ($args as $x) {
            var_dump($x);
        }
        echo '</pre>';
        die(1);
    }
}
