<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// для запрета доступа к остальным файлам
define('VG_ACCESS', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

// базовые настройки для быстрого разворачивания на другом хостинге
require_once 'config.php';
// фундаментальные настройки, пути к шаблонам, настройки безопасности сайта и т.д.
require_once 'core/base/settings/internal_settings.php';

function load($class_name)
{
    $class_name = str_replace('\\', '/', $class_name);
    include $class_name . '.php';
}

spl_autoload_register('load');

(new core\test\n2\A());