<?php

/**
 * common控制器
 */
class Common extends ControllerAdmin
{

    /**
     * 权限检查
     * @param type $ControllerName
     * @param type $Action
     * @param type $QueryString
     */
    public function __construct($ControllerName, $Action, $QueryString) {
        parent::__construct($ControllerName, $Action, $QueryString);
    }

    public function error404() {
        $this->show('views/error404.tpl');
    }

    public function error500() {
        $this->show('views/error500.tpl');
    }

}