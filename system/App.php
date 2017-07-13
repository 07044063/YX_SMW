<?php

if (!defined('APP_PATH')) {
    exit(0);
}

/**
 *  main Class
 */
class App
{

    const ROUTER_HASH_LIMIT = 30;

    // Singleton instance
    protected static $_instance = NULL;

    // Controller instance
    public $Controller = NULL;

    /**
     * App constructor.
     */
    public function __construct()
    {

    }

    /**
     * get App Class instance
     * @return object $_instance
     */
    public static function getInstance()
    {
        if (!self::$_instance instanceof self) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    /**
     * 打包请求参数
     * @param type $QueryString
     * @return Object QueryObject
     */
    private function packQueryString($QueryString)
    {
        if (!empty($QueryString)) {
            $QueryObject = new stdClass();
            $QueryString = explode('&', $QueryString);
            foreach ($QueryString as $r) {
                $r = explode('=', $r);
                if (count($r) == 2) {
                    $key = $r[0];
                    $QueryObject->$key = $r[1];
                }
            }
            return $QueryObject;
        } else {
            return NULL;
        }
    }

    /**
     * 处理请求
     * @global type $config
     * parse http request
     */
    public function parseRequest()
    {

        global $config;
        // 解析URI
        $URI = explode('/', $_SERVER["QUERY_STRING"]);

        // 得出相对根目录
        $config->webroot = preg_replace('/\w+\.php/i', '', htmlentities($_SERVER['PHP_SELF']));
        $config->webroot = str_replace('//', '/', $config->webroot);

        // 解析路由参数
        $RouteParam = $this->genRouterParams($URI);

        // 没有设置domain的情况下
        if (empty($config->domain) || $config->domain == '__DOMAIN__') {
            $config->domain = Util::getHOST();
        }

        try {
            if (class_exists($RouteParam->controller)) {
                // 实例化控制器
                $this->Controller = new $RouteParam->controller($RouteParam->controller, $RouteParam->action, $RouteParam->queryString);
                if (method_exists($this->Controller, $RouteParam->action)) {
                    // 注册当前URI
                    $this->Controller->uri = $URI = preg_replace('/\/\?\/$/', '', Util::getURI());
                    //检查权限
                    $authCheck = Util::checkAuth($RouteParam->controller, $RouteParam->action);
                    $flag = intval($authCheck[0]['res']);
                    slog("auth check res is $flag");
                    if (!$flag) {
                        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest") {
                            // ajax 请求的处理方式
                            return Controller::echoJson(['ret_code' => -1,
                                'ret_msg' => '没有操作权限']);
                        } else {
                            // 正常请求的处理方式
                            if (Controller::inWechat()) {
                                $error_url = 'Location: ?/Wxpage/noauth';
                            } else {
                                $error_url = 'Location: ?/Common/noauth';
                            }
                            header($error_url);
                        };
                        return;
                    }
                    // 回调根目录
                    $this->Controller->root = Util::getROOT();
                    // 调用对应方法
                    if (method_exists($this->Controller, 'beforeLoad') && $this->Controller->beforeLoad() !== FALSE) {
                        $this->Controller->{$RouteParam->action}($this->packQueryString($RouteParam->queryString));
                    }
                } else {
                    header('Location: ?/Common/error404');
                    throw new Exception("访问错误：方法不存在 {$RouteParam->controller}->{$RouteParam->action}() 不存在");
                }
            } else {
                header('Location: ?/Common/error404');
                throw new Exception("访问错误：控制器 {$RouteParam->controller} 不存在");
            }
        } catch (Exception $ex) {
            Util::log($ex->getMessage());
        }

    }

    /**
     * 生成路由处理参数
     * @global object $config
     * @param string $URI
     * @return \stdClass
     */
    final private function genRouterParams(&$URI)
    {
        global $config;
        $RouteParam = new stdClass();
        $RouteParam->queryString = "";
        if (isset($GLOBALS['controller'])) {
            $RouteParam->controller = $GLOBALS['controller'];
            $RouteParam->action = $this->getAction($config, $RouteParam->controller, $GLOBALS['action']);
        } else {
            if ($URI[1] == "" || strpos($URI[1], '=')) {
                $RouteParam->controller = $config->default_controller;
                if (strpos($URI[1], '=')) {
                    $RouteParam->queryString = $URI[1];
                }
            } else {
                $RouteParam->controller = $URI[1];
                $RouteParam->queryString = isset($URI[3]) ? $URI[3] : '';
            }
            $RouteParam->action = $this->getAction($config, $RouteParam->controller, isset($URI[2]) && preg_match("/\w+\_?\w?/is", $URI[2]) ? $URI[2] : "");
        }

        return $RouteParam;
    }


    /**
     * action转换
     * @param type $config
     * @param type $Controller
     * @param type $Action
     * @return type
     */
    private function getAction($config, $Controller, $Action)
    {
        if ($Action == "") {
            if (array_key_exists($Controller, $config->defaultAction)) {
                return $config->defaultAction[$Controller];
            } else {
                return 'index';
            }
        } else {
            // Action&querystring
            if (strstr($Action, "&")) {
                return substr($Action, 0, strpos($Action, "&"));
            }
            return $Action;
        }
    }

}