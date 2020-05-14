<?php

namespace core\base\controllers;

use core\base\exceptions\RouteException;
use core\base\settings\Settings;

class RouteController extends BaseController
{
    use Singleton;

    protected $routes;

    // шаблон проектирования "Одиночка (англ. Singleton)"  порождающий шаблон проектирования, гарантирующий, что в однопоточном приложении будет единственный экземпляр некоторого класса, и предоставляющий глобальную точку доступа к этому экземпляру.
    // теперь не можем создать объект этого типа извне
    private function __construct()
    {
        // человеко понятный url (пользовательская часть) будет иметь вид
        // контроллер/ссылка/параметр/значение, например controller/catalog/iphone/color/red
        // без чпу контроллер/параметр/значение (news/id/4/text/good)
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

            $url = explode('/', substr($address_str, strlen(PATH)));

            if ($url[0] && $url[0] === $this->routes['admin']['alias']) {
                // админка
                array_shift($url);

                if ($url[0] && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . $this->routes['plugins']['path'] . $url[0])) {
                    // обращаемся к плагину
                    $plugin = array_shift($url);
                    $pluginSettings = $this->routes['settings']['path'] . ucfirst($plugin . 'Settings');

                    if (file_exists($_SERVER['DOCUMENT_ROOT'] . PATH . $pluginSettings . '.php')) {
                        $pluginSettings = str_replace('/', '\\', $pluginSettings);
                        $this->routes = $pluginSettings::get('routes');
                    }

                    $dir = $this->routes['plugins']['dir'] ? '/' . $this->routes['plugins']['dir'] . '/' : '/';
                    $dir = str_replace('//', '/', $dir);

                    $this->controller = $this->routes['plugins']['path'] . $plugin . $dir;
                    $hrUrl = $this->routes['plugins']['hrUrl'];
                    $route = 'plugins';

                } else {
                    $this->controller = $this->routes['admin']['path'];
                    $hrUrl = $this->routes['admin']['hrUrl'];
                    $route = 'admin';
                }

            } else {
                // пользовательская часть
                $hrUrl = $this->routes['user']['hrUrl'];
                $this->controller = $this->routes['user']['path'];
                $route = 'user';
            }

            $this->createRoute($route, $url);

            if (isset($url[1])) {
                $count = count($url);
                $key = '';

                if (!$hrUrl) {
                    $i = 1;

                } else {
                    $this->parameters['alias'] = $url[1];
                    $i = 2;
                }

                for ( ; $i < $count; $i++) {

                    if (!$key) {
                        $key = $url[$i];
                        $this->parameters[$key] = '';

                    } else {
                        $this->parameters[$key] = $url[$i];
                        $key = '';
                    }
                }
            }

        } else {
            try {
                throw new \Exception('Не корректная директория сайта');

            } catch (\Exception $e) {
                exit($e->getMessage());
            }
        }
    }

    private function createRoute($var, $arr)
    {
        // arr - массив содержащий разобранную строку url
        // var - раздел, например user
        $route = [];

        if (!empty($arr[0])) {
            // путь пришел из url
            if (isset($this->routes[$var]['routes'][$arr[0]])) {
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