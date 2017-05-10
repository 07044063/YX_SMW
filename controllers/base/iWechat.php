<?php

if (!defined('APP_PATH')) {
    exit(0);
}

/**
 * 微信消息入口
 * @description Holp You Do Good But Not Evil
 *
 */
class iWechat extends Controller
{

    /**
     * @var WechatPostObject $postObj
     */
    public function index()
    {

        //设置utf-8应该是最最最关键的一步
        //header("Content-Type:text/html; charset=utf-8");

        $sVerifyMsgSig = $_GET["msg_signature"];
        $sVerifyTimeStamp = $_GET["timestamp"];
        $sVerifyNonce = $_GET["nonce"];
        $sVerifyEchoStr = $_GET["echostr"];

        //header('content-type:text');//以及这个地方配置内容的格式

        // 需要返回的明文
        $sEchoStr = "";

        $wxcpt = new WXBizMsgCrypt(TOKEN, EncodingAESKey, APPID);
        $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
        if ($errCode == 0) {
            //
            // 验证URL成功，将sEchoStr返回
            print($sEchoStr);
        } else {
            Util::log($errCode);
        }

    }
}
