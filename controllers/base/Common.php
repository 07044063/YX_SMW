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
    public function __construct($ControllerName, $Action, $QueryString)
    {
        parent::__construct($ControllerName, $Action, $QueryString);
    }

    public function error404()
    {
        $this->show('views/error404.tpl');
    }

    public function error500()
    {
        $this->show('views/error500.tpl');
    }

    public function noauth()
    {
        $this->show('views/noauth.tpl');
    }

    /**
     * 解析excel
     */
    private function analyseExcel($filePath = '', $sheet = 0)
    {
        include APP_PATH . 'lib/PHPExcel/Classes/PHPExcel.php';//引入PHP EXCEL类
        include APP_PATH . 'lib/PHPExcel/Classes/PHPExcel/Reader/Excel2003XML.php';
        if (empty($filePath) or !file_exists($filePath)) {
            die('file not exists');
        }
        $PHPReader = new PHPExcel_Reader_Excel2003XML();        //建立reader对象
        if (!$PHPReader->canRead($filePath)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($filePath)) {
                echo 'no Excel';
            }
        }
        $PHPExcel = $PHPReader->load($filePath);        //建立excel对象
        $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
        $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/
        $allRow = $currentSheet->getHighestRow();        //**取得一共有多少行*/
        $this->data = array();
        for ($rowIndex = 1; $rowIndex <= $allRow; $rowIndex++) {        //循环读取每个单元格的内容。注意行从1开始，列从A开始
            for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex++) {
                $addr = $colIndex . $rowIndex;
                if ($addr == "") {
                    continue;
                } else {
                    $cell = $currentSheet->getCell($addr)->getValue();
                    if ($cell instanceof PHPExcel_RichText) {
                        $cell = $cell->__toString();
                    }
                    $this->data[$rowIndex][$colIndex] = $cell;
                }
            }
        }
        return $this->data;
    }

    //导入EXCEL
    public function importExcel()
    {
        $action = $this->pPost('action');
        $filePath = $this->pPost('filePath');
        if (!empty ($filePath)) {
            $file_types = explode(".", $filePath);
            $file_type = $file_types [count($file_types) - 1];
            //判别是不是.xls文件，判别是不是excel文件
            if (strtolower($file_type) != "xls") {
                return $this->echoMsg(1, '请上传xls文件');
            } else {
                $this->analyseExcel($filePath);
                $data = $this->data;
                $this->loadModel(['mImport']);
                $res = $this->mImport->importExcel($data, $action);
                if ($res['code'] == 0) {
                    return $this->echoMsg(0, $res['msg']);
                } else {
                    return $this->echoMsg(1, $res['msg']);
                }
            }
        } else {
            return $this->echoMsg(1, '文件不能为空或者文件错误');
        }
    }

    //上传文件
    public function uploadFile()
    {
        global $config;
        $uid = $this->Session->get('uid') . '';
        $tempPath = $_FILES['file_data']['tmp_name'];
        $fileName = urlencode($_FILES['file_data']['name']);
        $file_types = explode(".", $fileName);
        $file_type = $file_types [count($file_types) - 1];
        $fname = time() . rand(100, 999) . $uid . '.' . $file_type;
        $uploadPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $fname;
        $uploadUrl = $config->domain . 'uploads' . DIRECTORY_SEPARATOR . $fname;
        try {
            move_uploaded_file($tempPath, $uploadPath);
            return $this->echoMsg(0, ['path' => $uploadPath, 'url' => $uploadUrl]);
        } catch (Exception $ex) {
            return $this->echoMsg(1, $ex->getMessage());
        }
    }

}