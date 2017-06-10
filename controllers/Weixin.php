<?php

/**
 * 系统控制器
 */
class Weixin extends ControllerWx
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


    public function getSignPackage()
    {
        $url = $this->pGet('url');
        slog('URL is @@@@ '.$url);
        $weObj = new Wechat($this->config->wxConfigs);
        $signPackage = $weObj->getJsSign($url);
        //Util::log($this->toJson($signPackage));
        return $this->echoMsg(0, $signPackage);
    }

}