<?php

namespace core\base\controllers;

trait BaseMethods
{
    protected $styles;
    protected $scripts;

    //будет инициализировать стили и скрипты из констант в настройках
    protected function init($admin = false)
    {
        if (!$admin) {
            $this->fillProperty(USER_CSS_JS['styles'], 'styles');
            $this->fillProperty(USER_CSS_JS['scripts'], 'scripts');

        } else {
            $this->fillProperty(ADMIN_CSS_JS['styles'], 'styles');
            $this->fillProperty(ADMIN_CSS_JS['scripts'], 'scripts');
        }
    }

    protected function fillProperty($properties, $propertyName)
    {
        if ($properties) {
            foreach ($properties as $item) {
                $this->$propertyName[] = PATH . TEMPLATE . trim($item, '/');
            }
        }
    }

    protected function clearStr($str)
    {
        if (is_array($str)) {
            foreach ($str as $key => $item) {
                $str[$key] = trim(strip_tags($item));
            }

            return $str;

        } else {
            return trim(strip_tags($str));
        }
    }

    protected function clearNun($num)
    {
        return 1 * $num;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function redirect($http = false, $code = 0)
    {
        if ($code) {
            $codes = ['301' => 'HTTP/1.1 301 Move Permanently'];

            if ($codes[$code]) {
                header($codes[$code]);
            }
        }

        if ($http) {
            $redirect = $http;
        } else {
            $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : PATH;
        }

        header('Location: ' . $redirect);
        exit();
    }

    protected function writeLog($message, $file = 'log.txt', $event = 'Fault')
    {
        $dateTime = new \DateTime();
        $str = $event . ": " . $dateTime->format("d-m-Y G:i:s") . " - " . $message . "\r\n";
        file_put_contents('log/' . $file, $str, FILE_APPEND);
    }
}