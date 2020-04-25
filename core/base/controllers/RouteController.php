<?php

namespace core\base\controllers;

class RouteController
{
    static private $_instance;

    // шаблон проектирования "Одиночка (англ. Singleton)"  порождающий шаблон проектирования, гарантирующий, что в однопоточном приложении будет единственный экземпляр некоторого класса, и предоставляющий глобальную точку доступа к этому экземпляру.
    private function __construct()
    {
        // теперь не можем создать объект этого типа
    }

    private function __clone()
    {
        // копию объекта тоже не сможем создать
    }

    public static function getInstance()
    {
        if (self::$_instance instanceof self) {
            return self::$_instance;
        }

        return self::$_instance = new self;
    }
}