<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/7
 * Time: 3:22
 */

namespace App\Services;

use App\Exceptions\ExampleException;
use App\Models\ApplyModel;
use App\Models\ApprovalModel;
use App\Models\LogModel;
use App\Models\StaffModel;
use App\Traits\ResponseTrait;

require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel.php');
require_once(base_path() . '/app/libs/PHPExcel-1.8/Classes/PHPExcel/Writer/Excel2007.php');

class ImportService extends BaseService
{
    use ResponseTrait;

    public function Import($data)
    {
        $filePath = base_path() . $data['file_url'];

        $suffix = pathinfo($filePath, PATHINFO_EXTENSION);

        #版本兼容
        if ($suffix == 'xls') {
            #创建读取实例 【xls】
            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
        } else {
            #创建读取实例 【xlsx】
            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        }

        $objPHPExcel = $objReader->load($filePath);//加载文件
        $import_data = $objPHPExcel->getsheet(0)->toArray();

        #删除前两行
        array_shift($import_data);

        if ($import_data[0][0] != '序号') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][1] != '姓名') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][2] != '性别') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][3] != '年龄') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][4] != '身份证号码') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][5] != '联系方式') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][6] != '回家时间') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][7] != '回家住址') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][9] != '来乐时间') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][10] != '返厂时间') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][11] != '返厂交通工具') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][12] != '交通工具上是否有疫情') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][13] != '健康状况') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][14] != '是否与高危人群有接触') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][15] != '接触人员及范围') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][16] != '隔离房间号') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][18] != '是否确诊为新型肺炎') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        if ($import_data[0][19] != '备注') {
            throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
        }

        array_shift($import_data);

        #获取公司人员身份证号码
        $model = new StaffModel();

        $where = [
            'enterprise_id' => $data['enterprise_id']
        ];

        $filed    = 'id_card';
        $id_cards = $model->GetList($filed, $where);

        $id_card = [];

        if ($id_cards['status'] == RETURN_SUCCESS) {
            foreach ($id_cards['data'] as $val) {
                array_push($id_card, $val['id_card']);
            }
        }

        $add_data   = [];
        $update_num = 0;
        $add_num    = 0;
        $apply_num  = 0;

        foreach ($import_data as $value) {
            $len = count($value);

            if ($len < 20) {
                throw new ExampleException(CHINESE_MSG[TEMPLATE], TEMPLATE);
            }

            if (empty($value[4]) && empty($value[4])) {
                continue;
            }

            if ($value[18] == '是') {
                $apply_num++;
            }

            if (in_array($value[4], $id_card)) {
                #更新操作
                $update = [
                    'staff_name'       => $value[1],
                    'sex'              => $value[2] == '女' ? 2 : 1,
                    'age'              => $value[3],
                    'id_card'          => $value[4],
                    'staff_phone'      => $value[5],
                    'return_home_date' => $value[6],
                    'staff_address'    => $value[7],
                    'epidemic_info'    => $value[8],
                    'return_date'      => $value[9],
                    'business_date'    => $value[10],
                    'trip'             => $value[11],
                    'vehicle'          => $value[12],
                    'temperature'      => $value[13],
                    'is_contact'       => $value[14] == '是' ? 1 : 2,
                    'contact_crowd'    => $value[15],
                    'room_num'         => $value[16],
                    'is_quarantine'    => $value[17] == '疑似' ? 1 : 2,
                    'is_diagnosis'     => $value[18] == '是' ? 1 : 2,
                    'remark'           => $value[19],
                    'utime'            => time(),
                    'u_user_id'        => $this->user_id,
                ];

                $where = [
                    'id_card' => $value[4]
                ];

                $model->EditData($where, $update);

                $update_num++;
            } else {
                #添加操作
                $add = [
                    'enterprise_id'    => $data['enterprise_id'],
                    'address_id'       => $data['address_id'],
                    'staff_name'       => $value[1],
                    'sex'              => $value[2] == '女' ? 2 : 1,
                    'age'              => $value[3],
                    'id_card'          => $value[4],
                    'staff_phone'      => $value[5],
                    'return_home_date' => $value[6],
                    'staff_address'    => $value[7],
                    'epidemic_info'    => $value[8],
                    'return_date'      => $value[9],
                    'business_date'    => $value[10],
                    'trip'             => $value[11],
                    'vehicle'          => $value[12],
                    'temperature'      => $value[13],
                    'is_contact'       => $value[14] == '是' ? 1 : 2,
                    'contact_crowd'    => $value[15],
                    'room_num'         => $value[16],
                    'is_quarantine'    => $value[17] == '疑似' ? 1 : 2,
                    'is_diagnosis'     => $value[18] == '是' ? 1 : 2,
                    'remark'           => $value[19],
                    'ctime'            => time(),
                    'c_user_id'        => $this->user_id,
                    'utime'            => time(),
                    'u_user_id'        => $this->user_id,
                ];

                array_push($add_data, $add);
                $add_num++;
            }
        }

        #获取复工审批信息
        $where = [
            'enterprise_id' => $data['enterprise_id'],
            'state' => [4]
        ];

        $raa = (new ApplyModel())->GetOne('a.*', $where);

        if ($raa['status'] == RETURN_SUCCESS && $apply_num > 0) {
            #修改审批为不通过
            (new ApplyModel())->EditData(['id' => $raa['data']['id']], ['state' => 6]);

            #修改审批状态
            $where = [
                'apply_id' => ['id' => $raa['data']['id']],
                'type' => 3
            ];

            $set = [
                'state' => 4
            ];

            (new ApprovalModel())->EditData($where, $set);
        }

        if (!empty($add_data)) {
            $model->AddAll($add_data);
        }

        #写入日志
        $log_data = [
            'address_id'      => $data['address_id'],
            'enterprise_id'   => $data['enterprise_id'],
            'enterprise_name' => $data['enterprise_name'],
            'file_name'       => $data['file_name'],
            'file_url'        => $data['file_url'],
            'update_num'      => $update_num,
            'add_num'         => $add_num,
            'import_time'     => $data['import_time'],
            'ctime'           => time(),
            'c_user_id'       => $this->user_id,
        ];

        $log_model = new LogModel();
        $res       = $log_model->AddData($log_data);

        return $this->HandleData('add', $res);
    }
}