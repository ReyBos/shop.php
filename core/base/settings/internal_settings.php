<?php

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
    'styles' => [],
    'scripts' => [],
];
// пути к файлам для пользовательской части
const USER_CSS_JS = [
    'styles' => [],
    'scripts' => [],
];