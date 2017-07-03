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
//        $PHPReader = new PHPExcel_Reader_Excel2003XML();        //建立reader对象
        $PHPReader = new PHPExcel_Reader_Excel2007();        //建立reader对象f
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
                $this->data[$rowIndex]['INDEX'] = $rowIndex; //把EXCEL的行号也读进去
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
            if (strtolower($file_type) != "xls" && strtolower($file_type) != "xlsx") {
                return $this->echoMsg(1, '请上传xls或者xlsx文件');
            } else {
                $this->analyseExcel($filePath);
                $data = $this->data;
                $this->loadModel(['mImport']);
                $res = $this->mImport->importExcel($data, $action);
                return $this->echoMsg(0, $res);
            }
        } else {
            return $this->echoMsg(1, '文件不能为空或者文件错误');
        }
    }

    //上传文件
    public function uploadFile()
    {
        $uid = $this->Session->get('uid') . '';
        $tempPath = $_FILES['file_data']['tmp_name'];
        $fileName = $_FILES['file_data']['name'];
        $file_types = explode(".", $fileName);
        $file_type = $file_types [count($file_types) - 1];
        $fname = time() . rand(100, 999) . $uid . '.' . $file_type;
        $uploadPath = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $fname;
        try {
            move_uploaded_file($tempPath, $uploadPath);
            return $this->echoMsg(0, $uploadPath);
        } catch (Exception $ex) {
            return $this->echoMsg(1, $ex->getMessage());
        }
    }

    //上传文件from Weixin
    public function downloadPicFromWx()
    {
        $weObj = new Wechat($this->config->wxConfigs);
        $mid = $this->pPost('mid');
        $rawdata = $weObj->getMedia($mid);
        global $config;
        $uid = $this->Session->get('uid') . '';
        $fname = time() . rand(100, 999) . $uid . '.jpg';
        $strt = DIRECTORY_SEPARATOR;
        $uploadPath = dirname(__FILE__) . $strt . '..' . $strt . '..' . $strt . 'uploads' . $strt . 'pic' . $strt . $fname;
        $uploadUrl = $config->domain . 'uploads' . $strt . 'pic' . $strt . $fname;
        try {
            //以读写方式打开一个文件，若没有，则自动创建
            $resource = fopen($uploadPath, 'w+');
            //将图片内容写入上述新建的文件
            fwrite($resource, $rawdata);
            //关闭资源
            fclose($resource);
            return $this->echoMsg(0, ['path' => $uploadPath, 'url' => $uploadUrl]);
        } catch (Exception $ex) {
            Util::log($ex->getMessage());
            return $this->echoMsg(1, $ex->getMessage());
        }
    }

}