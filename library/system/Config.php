<?php

namespace library\system;


class Config
{
    private static $config = [];

    /**
     * @param string $filename
     * @return array
     * @throws \Exception
     */
    public static function load(string $filename)
    {
        $file = ROOT . '/library/' . $filename . '.php';
        if (!file_exists($file)) {
            throw new \Exception('Config file: ' . $file . ' does not exist');
        }

        if (!isset(static::$config[$filename])) {
            static::$config[$filename] = require $file;
        }

        return static::$config[$filename];
    }
}