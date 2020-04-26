<?php

namespace core\base\controllers;

use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    static private $_instance;

    // шаблон проектирования "Одиночка (англ. Singleton)"  порождающий шаблон проектирования, гарантирующий, что в однопоточном приложении будет единственный экземпляр некоторого класса, и предоставляющий глобальную точку доступа к этому экземпляру.
    // теперь не можем создать объект этого типа извне
    private function __construct()
    {
        $routes = Settings::getInstance();
        $routes2 = ShopSettings::getInstance();
        exit();
    }

    private function __clone()
    {
        // копию объекта тоже не сможем создать извне
    }

    public static function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }

        return self::$_instance = new self;
    }
}