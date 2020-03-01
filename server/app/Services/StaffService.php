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
use Illuminate\Support\Facades\DB;

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
     * @param $param
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function StaffList($param, $is_page, $per_page)
    {
        $where = array_filter([
            'con' => $param['con'] ?? ''
        ]);

        $filed = 'id, staff_name, staff_phone, referrer_name, referrer_phone, company, state, is_del';

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
     * 核销
     * @param $param
     * @return array
     */
    public function EditState($param)
    {
        $set = [
            'state'     => 2,
            'utime'     => time(),
            'u_user_id' => $this->user_id,
        ];

        $res = DB::table('hhr_staff')
            ->whereIn('id', $param['id'])
            ->update($set);

        return $this->HandleData('edit', $res);
    }


    /**
     * 创建员工
     * @param $params
     * @return array
     */
    public function CreateStaff($params)
    {
        $data = [
            'staff_name'     => $params['staff_name'],
            'staff_phone'    => $params['staff_phone'],
            'referrer_name'  => $params['referrer_name'] ?? '',
            'referrer_phone' => $params['referrer_phone'] ?? '',
            'company'        => $params['company'],
            'state'          => 1,
            'ctime'          => time(),
            'c_user_id'      => $this->user_id,
            'utime'          => time(),
            'u_user_id'      => $this->user_id,
        ];

        $res = $this->staff_model->AddData($data);

        return $this->HandleData('add', $res);
    }
}