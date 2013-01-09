<?php

/* MEMO
construct should be private
getInstance should be static
*/

$obj1 = Singleton::getInstance();
$obj2 = Singleton::getInstance();

echo $obj1 === $obj2;

class Singleton
{
    private static $_singleton = null;

    private function __construct()
    {
        echo "Creat instance\n";
    }

    public static function getInstance()
    {
        if (is_null(self::$_singleton)) {
            self::$_singleton = new self();
        }
        return self::$_singleton;
    }
}
