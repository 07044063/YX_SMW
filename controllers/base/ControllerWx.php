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
        slog($this->getWxUserId());
        if (!$this->getWxUserId()) {
            $this->redirect("?/Index/wxerror");
        }
    }
}