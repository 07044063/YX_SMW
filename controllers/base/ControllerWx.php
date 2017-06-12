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
            $this->Session->set('uid', $admininfo['id']);
            $this->Session->set('uname', $admininfo['person_name']);
            $this->Session->set('utype', $admininfo['person_type']);
            $this->Session->set('uorg', $admininfo['org_id']);
        }
    }
}