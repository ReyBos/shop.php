<?php

use core\base\exceptions\RouteException;

defined('VG_ACCESS') or die('Access denied');

// шаблоны пользовательской части
const TEMPLATE = 'templates/default/';
const ADMIN_TEMPLATE = 'core/admin/views/';

// например если захотим разлогинить пользователей, поменяем версию кук
const COOKIE_VERSION = '1.0.0';
// ключ для шифрования
const CRYPT_KEY = '';
// время бездействия, если пользователь был не активен столько минут - разлогин
const COOKIE_TIME = 60;
// на какое время блочим при попытке подобрать пароль
const BLOCK_TIME = 3;

// количество товаров на странице
const QTY = 8;
// количество ссылок в постраничной навигации, правее и левее от активной
const QTY_LINKS = 3;

// пути к файлам для админки
const ADMIN_CSS_JS = [
    'styles' => ['site-admin.css'],
    'scripts' => ['site-admin.js'],
];
// пути к файлам для пользовательской части
const USER_CSS_JS = [
    'styles' => ['site-user.ccs'],
    'scripts' => ['site-user.js'],
];

function autoloadMainClasses($class_name)
{
    $class_name = str_replace('\\', '/', $class_name);

    if (!file_exists($class_name . '.php')) {
        throw new RouteException('Неверное имя файла для поключения - ' . $class_name . '.php');
    }

    include_once $class_name . '.php';
}

spl_autoload_register('autoloadMainClasses');