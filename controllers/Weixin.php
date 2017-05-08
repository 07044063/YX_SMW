<?php

/**
 * 系统控制器
 */
class Weixin extends ControllerAdmin
{
    /**
     *
     */
    public function getAccessToken()
    {
        $weObj = new Wechat($this->config->wxConfigs);
//        $weObj->valid(); //注意, 企业号与普通公众号不同，必须打开验证，不要注释掉
//        echo $weObj->checkAuth();
        return $this->echoMsg(0, $weObj->checkAuth());
    }

}