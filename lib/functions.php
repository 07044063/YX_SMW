<?php

!defined('APP_PATH') && define('APP_PATH', dirname(__DIR__).DIRECTORY_SEPARATOR);

//
////注册composer组件包
//$composer_file=str_replace('\\','/',APP_PATH.'vendor/autoload.php');
//if(file_exists($composer_file)){
//    include $composer_file;
//}
//
//
///**
// * 友好打印函数
// * @param  mixed $obj [要打印的对象]
// * @return void
// */
//function p($obj,$sql=false,$die=false)
//{
//    if($sql){
//        $sql='【 '.Dao::get_instance()->getSql();
//        $sql.=' || '.db()->getLastSql().' 】';
//        is_array($obj)?($obj['last_sql']= $sql):($obj .= $sql);
//    }
//	echo '<pre>'.print_r($obj,true).'</pre>';
//
//    $die && die;
//}
//

/**
 * slog调试函数
 * 具体请参考下面这个东东
 * https://github.com/luofei614/SocketLog
 */
function slog($log,$type='log',$css='')
{
    if(is_string($type))
    {
        $type=preg_replace_callback('/_([a-zA-Z])/',create_function('$matches', 'return strtoupper($matches[1]);'),$type);

        if(method_exists('\Util\SocketLog',$type) || in_array($type,\Util\SocketLog::$log_types))
        {
           return  call_user_func(array('\Util\SocketLog',$type),$log,$css);
        }
    }

    if(is_object($type) && 'mysqli'==get_class($type))
    {
           return \Util\SocketLog::mysqlilog($log,$type);
    }

    if(is_resource($type) && ('mysql link'==get_resource_type($type) || 'mysql link persistent'==get_resource_type($type)))
    {
           return \Util\SocketLog::mysqllog($log,$type);
    }

    if(is_object($type) && 'PDO'==get_class($type))
    {
           return \Util\SocketLog::pdolog($log,$type);
    }

    throw new Exception($type.' is not SocketLog method');
}


/**
 * json 快捷函数
 * @param  mixed   $ret_code [要打印的数组或者数值]
 * @param  string  $ret_msg  [消息]
 * @param  boolean $sql      [是否打印sql,待定！]
 */
function json($ret_code='',$ret_msg='')
{
    header('Content-Type: application/json; charset=utf-8');

    $arr=is_numeric($ret_code)?array(
            'ret_code' => $ret_code,
            'status'   => $ret_code, #兼容某些页面的js
            'ret_msg' => $ret_msg,
            'msg'     => $ret_msg
        ):$ret_code;

    if (strpos(PHP_VERSION, '5.3') > -1) {
        // php 5.3-
        echo json_encode($arr);
    } else {
        // php 5.4+
        echo json_encode($arr,JSON_UNESCAPED_UNICODE);
    }

    die;
}



/**
 * 实例化一个没有模型文件的连接
 * @param string $name 数据库名称 支持指定基础模型 例如 
 * @param string $tablePrefix 表前缀
 * @param mixed $connection 数据库连接信息
 * @return object
 */
function db($name='', $connection='') {
    static $_model  = array();

    if(strpos($name,':')) {
        list($class,$name)    =  explode(':',$name);
    }else{
        $class      =   'Util\\Model';
    }

    $guid = (is_array($connection)?implode('',$connection):$connection) . $name . '_' . $class;

    if (!isset($_model[$guid])){
        $_model[$guid] = new $class($name,$connection);
    }
    return $_model[$guid];
}


function Model($name='')
{
    if(empty($name)) return M();

    static $_model  =   array();

    $class          =   'Model\\'.basename($name);

    if(class_exists($class)) {
        $cfg=config('db');
        $model      =   new $class(basename($name),null);
    }else {
        $model      =   db(basename($name));
    }

    $_model[$name]  =  $model;

    return $model;
}

/**
 * 获取和设置配置参数 支持批量定义 抛弃global $config
 * @param string|array $name 配置变量
 * @param mixed $value 配置值
 * @param mixed $default 默认值
 * @return mixed
 */
function config($name=null, $value=null,$default=null) {
    global $config;
    static $_config;
    $_config=$_config?$_config:(array)$config;
    if (empty($name)) {
        return $_config;
    }
    // 优先执行设置获取或赋值
    if (is_string($name)) {
        if (!strpos($name,'.')) {
            if (is_null($value))
                return isset($_config[$name]) ? $_config[$name] : $default;
            $_config[$name] = $value;
            return null;
        }
        // 二维数组设置和获取支持
        $name = explode('.', $name);
        $name[0]   =  $name[0];
        if (is_null($value))
            return isset($_config[$name[0]][$name[1]]) ? $_config[$name[0]][$name[1]] : $default;
        $_config[$name[0]][$name[1]] = $value;
        return null;
    }
    // 批量设置
    if (is_array($name)){
        $_config = array_merge($_config, array_change_key_case($name,CASE_UPPER));
        return null;
    }
    return null; // 避免非法参数
}


/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}



/**
 * 是否是AJAx提交的
 * @return bool
 */
function isAjax(){
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        return true;
    }else{
        return false;
    }
}

/**
 * 是否是GET提交的
 */
function isGet(){
    return $_SERVER['REQUEST_METHOD'] == 'GET' ? true : false;
}

/**
 * 是否是POST提交
 * @return int
 */
function isPost() {
    return ($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("~https?:\/\/([^\:\/]+).*~i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("~([^\:]+).*~", "\\1", $_SERVER['HTTP_HOST']))) ? 1 : 0;
}



/**
 * 把返回的数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pid parent标记字段
 * @param string $level level标记字段
 * @return array
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0)
{
    // 创建Tree
    $tree = array();
    if (is_array($list)) {
        // 创建基于主键的数组引用
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            // 判断是否存在parent
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent           = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}


function gbk_to_utf8($str){
    return mb_convert_encoding($str, 'utf-8', 'gbk');
}

function utf8_to_gbk($str){
    return mb_convert_encoding($str, 'gbk', 'utf-8');
}



/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '')
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return round($size, 2) . $delimiter . $units[$i];
}

/**
 *  
 * @author zsoner zsoner@wsxhr.com
 * @date   2016-08-15
 * @param  mixed    $wechat_id 微信ID号 当参数为true时,返回的是全部配置
 * @return array                 
 */
function getWechatConfig($wechat_id=0){

    $wechats=db('wechats')->getField('wechat_id,wechat_name,token,account,app_id,app_secret,encodingaeskey');

    $wechats[0]=array(
            'wechat_id'=>0,
            'wechat_name'=>WECHAT_NAME,
            'token'=>TOKEN,
            'account'=>WECHAT_ACCOUNT,
            'app_id'=>APPID,
            'app_secret'=>APPSECRET,
            'encodingaeskey'=>EncodingAESKey
        );

    return ($wechat_id===true)?$wechats:$wechats[$wechat_id];
}


function getPage(){
    return $_GET['page']?(int)($_GET['page']):($_POST['page']?(int) $_POST['page']:1);
}

?>