<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/7
 * Time: 17:19
 */

namespace App\Services;


use App\Models\AreaModel;
use App\Models\EnterpriseModel;
use App\Models\LogModel;
use App\Models\ProgrammeModel;
use App\Models\StaffModel;
use App\Traits\ResponseTrait;

class StaffService extends BaseService
{
    use ResponseTrait;

    /** @var StaffModel */
    protected $staff_model;

    /** @var EnterpriseModel */
    protected $enterprise_model;

    /** @var ProgrammeModel */
    protected $programme_model;

    public function __construct()
    {
        parent::__construct();
        $this->staff_model      = new StaffModel();
        $this->enterprise_model = new EnterpriseModel();
        $this->programme_model  = new ProgrammeModel();
    }

    /**
     * 企业员工列表
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function StaffList($params, $is_page, $per_page)
    {
        $filed = 'id, staff_name, staff_phone, return_date, is_contact, temperature, is_quarantine, is_diagnosis, business_date';

        $where = [
            'enterprise_id' => $params['enterprise_id']
        ];

        $result = $this->staff_model->GetList($filed, $where, [], '', '', $is_page, $per_page);

        return $result;
    }

    /**
     * 人员信息
     * @param $params
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function StaffDetail($params)
    {
        $filed = 'a.id, a.staff_name, a.sex, a.age, a.staff_address, a.return_home_date, a.epidemic_info, a.remark, a.vehicle, a.id_card, a.staff_phone, a.return_date, a.business_date, a.is_contact, a.temperature, a.trip, a.contact_crowd, a.room_num, a.is_quarantine, a.is_diagnosis, s.enterprise_name';

        $where = [
            'id' => $params['id']
        ];

        $user_alias = MODEL_ALIAS[$this->enterprise_model->TableName()];

        $join = [
            [
                'left',
                $this->enterprise_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.id', '=', 'a.enterprise_id'],
                ],
            ],
        ];

        $result = $this->staff_model->GetOne($filed, $where, $join);

        return $result;
    }

    /**
     * 企业列表
     * @param $params
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function EnterpriseList($params)
    {
        $filed = 'a.id, a.enterprise_name, a.address_id';

        $where = array_filter([
            'enterprise_name' => $params['enterprise_name'] ?? '',
            'address_id'      => $params['address_id'] ?? '',
            'is_del'          => 1,
        ]);

        if (empty($params['address_id']) && $this->user_info['management_area'] != "All") {
            $where['address_id_in'] = explode(',', $this->user_info['management_area']);
        }

        $result = $this->enterprise_model->GetList($filed, $where, []);

        return $result;
    }

    /**
     * 上报防疫方案
     * @param $params
     * @return array
     */
    public function EditProgramme($params)
    {
        $ret = $this->programme_model->CheckUser(['enterprise_id' => $params['enterprise_id']]);

        if ($ret) {
            #更新操作
            $where = [
                'enterprise_id' => $params['enterprise_id'],
            ];

            $data = [
                'programme' => $params['programme'],
                'utime'     => time(),
            ];

            $res = $this->programme_model->EditData($where, $data);

            return $this->HandleData('edit', $res);
        } else {
            #添加操作
            $data = [
                'enterprise_id' => $params['enterprise_id'],
                'programme'     => $params['programme'],
                'utime'         => time(),
            ];

            $res = $this->programme_model->AddData($data);

            return $this->HandleData('add', $res);
        }

    }

    /**
     * 上报日志
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function ImportLog($is_page, $per_page)
    {
        #条件
        $where = [
            'c_user_id' => $this->user_id
        ];

        $filed = 'a.id, a.enterprise_name, a.file_name, a.update_num, a.add_num, a.state, a.import_time, area.pac_name as address';

        $log_model  = new LogModel();
        $area_model = new AreaModel();

        $user_alias = MODEL_ALIAS[$area_model->TableName()];

        $join = [
            [
                'left',
                $area_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.id', '=', 'a.address_id'],
                ],
            ],
        ];

        $result = $log_model->GetList($filed, $where, $join, '', 'a.id', $is_page, $per_page);

        return $result;
    }

    /**
     * 防疫方案内容
     * @param $params
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function Programme($params)
    {
        $where = [
            'enterprise_id' => $params['enterprise_id']
        ];
        $filed = 'id, enterprise_id, programme';
        $res   = $this->programme_model->GetOne($filed, $where);

        return $res;
    }
}