<?php

use core\base\exceptions\RouteException;
use core\base\controllers\RouteController;

// для запрета доступа к остальным файлам
define('VG_ACCESS', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

// базовые настройки для быстрого разворачивания на другом хостинге
require_once 'config.php';
// фундаментальные настройки, пути к шаблонам, настройки безопасности сайта и т.д.
require_once 'core/base/settings/internal_settings.php';

try {
    RouteController::getInstance()->route();

} catch (RouteException $e) {
    exit($e->getMessage());
}