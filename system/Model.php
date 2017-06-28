<?php

if (!defined('APP_PATH')) {
    exit(0);
}

/**
 * System Super Class Model
 */

/**
 * @property Dao $Dao Data access Object
 * @property Email $Email
 * @property ImageUploader $ImageUploader
 * @property Db $Db
 * @property DigCrypt $DigCrypt
 * @property Smarty $Smarty Smarty
 * @property Auth $Auth Auth
 * @property Util $Util Util
 * @property Controller $Controller Controller
 * @property Load $Load Load
 * @property SqlCached $SqlCached SqlCached
 * @property Session $Session
 * @property mAdmin $mAdmin
 * @property mCommon $mCommon
 * @property mStock $mStock
 * @property mOrder $mOrder
 * @property mInventory $mInventory
 * @property mImport $mImport
 * @property mExport $mExport
 * @property mQuery $mQuery
 */
class Model
{

    /**
     * 控制器指针
     * @var Controller
     */
    protected $Controller;

    // 构造方法
    public function __construct()
    {
        // nothing
    }

    /**
     * magic get
     * @param type $name
     * @return type
     */
    function __get($name)
    {
        $class = $this->Controller;
        if (property_exists($class, $name)) {
            return $this->Controller->$name;
        }
    }

    /**
     * magic call
     * @param type $name
     * @param type $arguments
     */
    function __call($name, $arguments)
    {
        // 对Controller进行动态反射，跨对象调用
        $class = new ReflectionClass('Controller');
        try {
            $ec = $class->getMethod($name);
            return $ec->invokeArgs($this->Controller, $arguments);
        } catch (ReflectionException $re) {
            die('Fatal Error : ' . $re->getMessage());
        }
        return false;
    }

    /**
     * hook
     * 手动挂载model到另外一个model
     */
    public final function hook($classA = array())
    {
        foreach ($classA AS $class) {
            $className = get_class($class);
            $this->$className = $class;
            unset($className);
        }
    }

    /**
     * 链接控制器句柄
     * @param type $obj
     */
    public final function linkController(&$obj)
    {
        $this->Controller = $obj;
    }

}
