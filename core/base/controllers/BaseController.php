<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

abstract class BaseController
{
    use \core\base\controllers\BaseMethods;

    protected $page;
    protected $errors;
    protected $controller;
    protected $inputMethod;
    protected $outputMethod;
    protected $parameters;

    public function route()
    {
        $controller = str_replace('/', '\\', $this->controller);

        try {
            $object = new \ReflectionMethod($controller, 'request');
            $args = [
                'parameters' => $this->parameters,
                'inputMethod' => $this->inputMethod,
                'outputMethod' => $this->outputMethod,
            ];

            // вызываем метод который искали при создании объекта класса ReflectionMethod
            $object->invoke(new $controller, $args);

        } catch (\ReflectionException $e) {
            throw new RouteException($e->getMessage());
        }
    }

    public function request($args)
    {
        $this->parameters = $args['parameters'];
        $inputMethod = $args['inputMethod'];
        $outputMethod = $args['outputMethod'];

        $data = $this->$inputMethod();

        if (method_exists($this, $outputMethod)) {
            $page = $this->$outputMethod($data);
            if ($page) {
                $this->page = $page;
            }

        } elseif ($data) {
            $this->page = $data;
        }

        if ($this->errors) {
            $this->writeLog();
        }

        $this->getPage();
    }

    protected function render($path = '', $parameters = [])
    {
        extract($parameters);

        if (!$path) {
            $class = new \ReflectionClass($this);
            $space = str_replace('\\', '/', $class->getNamespaceName() . '\\');
            $routes = Settings::get('routes');

            if ($space === $routes['user']['path']) {
                $template = TEMPLATE;

            } else {
                $template = ADMIN_TEMPLATE;
            }

            $path = $template . explode('controller', strtolower($class->getShortName()))[0];
        }

        ob_start();

        if (!file_exists($path . '.php')) {
            throw new RouteException('Отсутствует шаблон - ' . $path);
        }
        include_once $path . '.php';

        return ob_get_clean();
    }

    protected function getPage()
    {
        if (is_array($this->page)) {
            foreach ($this->page as $block) {
                echo $block;
            }

        } else {
            echo $this->page;
        }

        exit();
    }
}