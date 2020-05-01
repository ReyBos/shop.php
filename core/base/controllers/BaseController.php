<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;

abstract class BaseController
{
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

        $this->$inputMethod();
        $this->page = $this->$outputMethod();

        if ($this->errors) {
            $this->writeLog();
        }

        $this->getPage();
    }

    protected function render($path = '', $parameters = [])
    {
        extract($parameters);

        if (!$path) {
            $path = TEMPLATE . explode('controller', strtolower((new \ReflectionClass($this))->getShortName()))[0];
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
        exit($this->page);
    }
}