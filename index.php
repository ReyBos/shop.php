<?php

// для запрета доступа к остальным файлам
define('VG_ACCESS', true);

header('Content-Type:text/html;charset=utf-8');
session_start();

// базовые настройки для быстрого разворачивания на другом хостинге
require_once 'config.php';
// фундаментальные настройки, пути к шаблонам, настройки безопасности сайта и т.д.
require_once 'core/base/settings/internal_settings.php';