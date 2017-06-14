<?php

/**
 * ControllerWx
 */
class ControllerWx extends Controller
{
    /**
     * ControllerAdmin constructor.
     * @param $ControllerName
     * @param $Action
     * @param $QueryString
     */
    public function __construct($ControllerName, $Action, $QueryString)
    {
        parent::__construct($ControllerName, $Action, $QueryString);
        if ($this->config->debug) {
            $this->Session->set('wxuid', $this->config->WxUserId);
        }
        $UserId = $this->getWxUserId();
        if (!$UserId) {
            $this->redirect("wxerror.php");
        } else {
            $this->loadModel('mAdmin');
            $admininfo = $this->mAdmin->get($UserId);
            $this->setUserSession($admininfo);
        }
    }
}