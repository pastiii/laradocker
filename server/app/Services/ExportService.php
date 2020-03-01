<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/16
 * Time: 9:57
 */

namespace App\Services;

use App\Models\StaffModel;

require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel.php');
require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

class ExportService extends BaseService
{
    public function Export($param)
    {
        $model = new StaffModel();

        $where = array_filter([
            'con' => $param['con'] ?? ''
        ]);

        $filed = 'id, staff_name, staff_phone, referrer_name, referrer_phone, company, state';

        $res = $model->GetList($filed, $where);


        $content = "人员名单";
        $title   = ['编号', '本人姓名', '本人电话', '推荐人姓名', '推荐人电话', '企业名称', '状态'];
        $result  = [];
        #数据处理
        if ($res['status'] == RETURN_SUCCESS) {
            $data = $res['data'];
            $i    = 1;
            foreach ($data as $val) {

                switch ($val['state']) {
                    case 1:
                        $state = '未核销';
                        break;
                    case 2:
                        $state = '已核销';
                        break;
                }

                $params = [
                    'id'                     => $i,
                    'staff_name'             => $val['staff_name'],
                    'staff_phone'            => $val['staff_phone'],
                    'referrer_name'          => $val['referrer_name'],
                    'referrer_phone'         => $val['referrer_phone'],
                    'company'                => $val['company'],
                    'state'                  => $state,
                ];

                array_push($result, $params);
                $i++;
            }
        }

        $path     = storage_path() . '/app/public/export/';
        $fileName = date('YmdHis') . 'YY' . rand(10000, 99999); //时间+随机数
        $ret      = $this->exportExcel($title, $result, $content, $fileName, $path);
        return $ret;
    }

    public function exportExcel($title = array(), $data = array(), $content, $fileName = '', $savePath = './', $isDown = true)
    {
        try {
            $obj = new \PHPExcel();

            //横向单元格标识
            $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
            $obj->getActiveSheet(0)->setTitle('sheet名称');   //设置sheet名称
            $_row = 1;   //设置纵向单元格标识
            if ($title) {
                $_cnt = count($title);
                $obj->getActiveSheet(0)->mergeCells('A' . $_row . ':' . $cellName[$_cnt - 1] . $_row);   //合并单元格
                $obj->setActiveSheetIndex(0)->setCellValue('A' . $_row, $content);
                $obj->getActiveSheet()->getStyle('A1')->getFont()->setName('宋体')//字体
                ->setSize(26); //字体大小
                $obj->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $_row++;
                $i = 0;
                foreach ($title AS $v) {   //设置列标题
                    $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i] . $_row, $v);
                    $i++;
                }
                $_row++;
            }
            //填写数据

            if ($data) {
                $i = 0;
                foreach ($data AS $_v) {
                    $j = 0;
                    foreach ($_v AS $_cell) {
                        $obj->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + $_row), $_cell);
                        $j++;
                    }
                    $i++;
                }
            }

            //文件名处理
            if (!$fileName) {
                $fileName = uniqid(time(), true);
            }

            $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');
            if ($isDown) {   //网页下载
                // Redirect output to a client’s web browser (Excel5)
                header('Content-Type: application/vnd.ms-excel');
                header("Content-Disposition: attachment;filename=$fileName.xls");
                header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $objWrite->save('php://output');
                exit;
            }

            $_fileName = iconv("utf-8", "gb2312", $fileName);   //转码
            $_savePath = $savePath . $_fileName . '.xlsx';
            $objWrite->save($_savePath);

            return $savePath . $fileName . '.xlsx';

        } catch (\Exception $e) {
            echo '  捕获到异常 :' . $e->getMessage();
            return false;
        }


    }
}