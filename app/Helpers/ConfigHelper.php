<?php


namespace App\Helpers;


use App\Models\Config;

class ConfigHelper
{
    private static $props = [];

    /* Get system config prop */
    public static function get($name)
    {
        if (empty(self::$props)) {
            self::set();
        }

        if (array_key_exists($name, self::$props)) {
            return self::$props[$name];
        }

        return null;
    }

    /* fetch system config from database */
    private static function set()
    {
        $config = (new Config())->find()->execute(\PDO::FETCH_ASSOC);
        self::$props = $config;
    }

}