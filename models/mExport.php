<?php

/**
 * Desc
 * @description Hope You Do Good But Not Evil
 */
class mExport extends Model
{

    public function exportExcel($data, $filename)
    {
        if ($data == []) {
            return ['code' => 1, 'msg' => '没有需要导出的数据'];
        }

        include APP_PATH . 'lib/PHPExcel/Classes/PHPExcel.php';

        include APP_PATH . 'lib/PHPExcel/Classes/PHPExcel/Writer/Excel2007.php';

        include APP_PATH . 'lib/PHPExcel/Classes/PHPExcel/Reader/Excel2007.php';

        $templateName = APP_PATH . 'exports/temp/' . $filename;

        $PHPReader = new PHPExcel_Reader_Excel2007();
        if (!$PHPReader->canRead($templateName)) {
            $PHPReader = new PHPExcel_Reader_Excel5();
            if (!$PHPReader->canRead($templateName)) {
                return ['code' => 1, 'msg' => '无法识别的Excel模版！'];
            }
        }

        $PHPExcel = $PHPReader->load($templateName);
        $currentSheet = $PHPExcel->getActiveSheet();        //**读取excel文件中的指定工作表*/
        $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/

        $url = $this->genXlsxFileType1($data, $PHPExcel, $PHPExcel->getActiveSheet(), $allColumn);
        return ['code' => 0, 'msg' => $url];
    }

    private function genXlsxFileType1($data, $PHPExcel, $Sheet, $allColumn)
    {
        global $config;
        $offset = 2;

        $Sheet->getStyle('A1')
            ->getAlignment()
            ->setVertical(PHPExcel_Style_Alignment::VERTICAL_BOTTOM);

        foreach ($data as $index => $da) {
            for ($colIndex = 'A'; $colIndex <= $allColumn; $colIndex++) {
                $Sheet->setCellValueExplicit($colIndex . $offset, $da[$colIndex], PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $offset++;
        }
        // 写入文件
        $objWriter = new PHPExcel_Writer_Excel2007($PHPExcel);
        $fileName = date('Ymd') . '-' . uniqid() . '.xlsx';
        $objWriter->save(APP_PATH . 'exports/export_files/' . $fileName);
        return $config->domain . 'exports/export_files/' . $fileName;
    }

}
