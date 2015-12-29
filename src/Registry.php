<?php

namespace Odango\OdangoFeed;

class Registry extends \Odango\OdangoPhp\Registry {
    private static $nyaaCollector;

    public static function init($dsn, $user, $pass)
    {
        static::setStash(new \Stash\Pool(new \Stash\Driver\Sqlite()));
        static::setDatabase(new \Ark\Database\Connection('mysql:dbname=odango', 'root'));
        static::setNyaa(new \Odango\OdangoPhp\Nyaa\Database());
        static::setNyaaCollector(new \Odango\OdangoPhp\NyaaCollector());
    }

    public static function getNyaaCollector()
    {
        return static::$nyaaCollector;
    }

    public static function setNyaaCollector($nyaaCollector)
    {
        static::$nyaaCollector = $nyaaCollector;
    }
}
