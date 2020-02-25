<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/16
 * Time: 9:57
 */

namespace App\Services;

use App\Models\ApplyModel;

require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel.php');
require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

class ExportService extends BaseService
{
    public function Export()
    {
        $model = new ApplyModel();

        $filed = 'id, enterprise_name, contacts, juridical_person_phone, FROM_UNIXTIME(start_time, "%Y-%m-%d") as start_time, staff_num, return_num, not_return_num, six_category, 
        isolation_room, is_disinfect, measure_temperature, is_propagate, state';

        $where = array_filter(
            [
                'enterprise_name' => $params['enterprise_name'] ?? ''
            ]
        );

        $res = $model->GetList($filed, $where);

        $content = "中小大工业企业复产复工情况统计表";
        $title   = ['序号', '企业名称', '疫情防控负责人', '负责人联系方式', '企业状态', '企业复产复工时间', '企业用工人数', '实际返岗人数', '县外人数', '六类人数', '设置隔离室', '是否消毒防控', '是否进行体温检测', '是否进行防疫宣传'];
        $result  = [];
        #数据处理
        if ($res['status'] == RETURN_SUCCESS) {
            $data = $res['data'];
            $i    = 1;
            foreach ($data as $val) {

                switch ($val['state']) {
                    case 1:
                        $state = '拟复产';
                        break;
                    case 2:
                        $state = '拟复产';
                        break;
                    case 3:
                        $state = '拟复产';
                        break;
                    case 4:
                        $state = '生产';
                        break;
                    case 5:
                        $state = '停产';
                        break;
                    case 6:
                        $state = '停产';
                        break;
                }

                $params = [
                    'id'                     => $i,
                    'enterprise_name'        => $val['enterprise_name'],
                    'contacts'               => $val['contacts'],
                    'juridical_person_phone' => $val['juridical_person_phone'],
                    'state'                  => $state,
                    'start_time'             => $val['start_time'],
                    'staff_num'              => $val['staff_num'],
                    'return_num'             => $val['return_num'],
                    'not_return_num'         => $val['not_return_num'],
                    'six_category'           => $val['six_category'],
                    'isolation_room'         => $val['isolation_room'] == 1 ? '是' : '否',
                    'is_disinfect'           => $val['is_disinfect'] == 1 ? '是' : '否',
                    'measure_temperature'    => $val['measure_temperature'] == 1 ? '是' : '否',
                    'is_propagate'           => $val['is_propagate'] == 1 ? '是' : '否',
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