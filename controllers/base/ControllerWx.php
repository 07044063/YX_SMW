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
        if (!$this->getWxUserId()) {
            $this->redirect("wxerror.php");
        }
    }
}