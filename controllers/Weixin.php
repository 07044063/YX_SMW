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
        $weObj = new Wechat($this->config->wxConfigs);
        $signPackage = $weObj->getJsSign($url);
        //Util::log($this->toJson($signPackage));
        return $this->echoMsg(0, $signPackage);
    }

    public function changeOrderStatus()
    {
        $postdata = $this->post();
        $order_id = intval($postdata['order_id']);
        $oldstatus = $postdata['oldstatus'];
        $status = array(
            'create' => 'receive',
            'receive' => 'ready',
            'ready' => 'check',
            'check' => 'send',
            'send' => 'delivery',
            'delivery' => 'done'
        );
        $newstatus = $status[$oldstatus];
        $uid = $this->Session->get('uid');
        $data = $this->Db->query("call p_update_order_status($order_id,'$oldstatus','$newstatus',$uid);");
        return $this->echoMsg((int)$data[0]['res'], '');
    }

}