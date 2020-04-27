<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;
use core\base\settings\ShopSettings;

class RouteController
{
    static private $_instance;

    protected $routes;

    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    // шаблон проектирования "Одиночка (англ. Singleton)"  порождающий шаблон проектирования, гарантирующий, что в однопоточном приложении будет единственный экземпляр некоторого класса, и предоставляющий глобальную точку доступа к этому экземпляру.
    // теперь не можем создать объект этого типа извне
    private function __construct()
    {
        $address_str = $_SERVER['REQUEST_URI'];
        if (strrpos($address_str, '/') === strlen($address_str) - 1 && strlen($address_str) !== 1) {
            $this->redirect(rtrim($address_str, '/'), 301);
        }

        $path = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], 'index.php'));
        if ($path === PATH) {
            $this->routes = Settings::get('routes');
            if (!$this->routes) {
                throw new RouteException('Сайт находится на техническом обслуживании');
            }

            if (strpos($address_str, $this->routes['admin']['alias']) === strlen(PATH)) {
                // админка

            } else {
                // пользовательская часть
                $url = explode('/', substr($address_str, strlen(PATH)));
                $hrUrl = $this->routes['user']['hrUrl'];
                $this->controller = $this->routes['user']['path'];
                $route = 'user';
            }

            $this->createRoute($route, $url);

            exit();

        } else {
            try {
                throw new \Exception('Не корректная директория сайта');

            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
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

    private function createRoute($var, $arr)
    {
        // arr - массив содержащий разобранную строку url
        // var - раздел, например user
        $route = [];

        if (!empty($arr[0])) {
            // путь пришел из url
            if ($this->routes[$var]['routes'][$arr[0]]) {
                // есть алиас для этого контроллера в классе Settings
                $route = explode('/', $this->routes[$var]['routes'][$arr[0]]);

                $this->controller .= ucfirst($route[0] . "Controller");

            } else {
                $this->controller .= ucfirst($arr[0] . "Controller");
            }

        } else {
            // нужно подрубить дефолтные классы
            $this->controller .= $this->routes['default']['controller'];
        }

        $this->inputMethod = $route[1] ?? $this->routes['default']['inputMethod'];
        $this->outputMethod = $route[2] ?? $this->routes['default']['outputMethod'];

        return;
    }
}