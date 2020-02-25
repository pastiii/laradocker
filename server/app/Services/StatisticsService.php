<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/9
 * Time: 12:37
 */

namespace App\Services;


use App\Models\AreaModel;
use App\Models\EnterpriseModel;
use App\Models\StaffModel;

class StatisticsService extends BaseService
{
    /** @var StaffModel */
    protected $staff_model;

    /** @var EnterpriseModel */
    protected $enterprise_model;

    /** @var AreaModel */
    protected $area_model;

    public function __construct()
    {
        parent::__construct();
        $this->staff_model      = new StaffModel();
        $this->enterprise_model = new EnterpriseModel();
        $this->area_model       = new AreaModel();
    }

    /**
     * 企业
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function Enterprise($params, $is_page, $per_page)
    {
        $where = array_filter([
            'enterprise_name' => $params['enterprise_name'] ?? '',
            'is_del'          => 1
        ]);

        if ($this->user_info['management_area'] != "All") {
            $where['address_id_in'] = explode(',', $this->user_info['management_area']);
        }

        $filed = "a.id, a.enterprise_name, count(f.id or null) total, count(f.is_contact=1 or null) as contact_num, count(f.temperature!='正常' or null) as temperature, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

        $user_alias = MODEL_ALIAS[$this->staff_model->TableName()];

        $join = [
            [
                'left',
                $this->staff_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.enterprise_id', '=', 'a.id'],
                ],
            ]
        ];

        $res = $this->enterprise_model->GetList($filed, $where, $join, '', 'a.id', $is_page, $per_page);

        return $res;
    }

    /**
     * 地区
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function Region($params, $is_page, $per_page)
    {
        $where = array_filter([
            'township' => $params['township'] ?? '',
        ]);

        if ($this->user_info['management_area'] != "All") {
            $where['id_in'] = explode(',', $this->user_info['management_area']);
        } elseif ($this->user_info['management_area'] == "All") {
            $where['id_in'] = LAOTING;
        }

        $filed = "a.id, a.township, count(f.id or null) total, count(f.is_contact=1 or null) as contact_num, count(f.temperature!='正常' or null) as temperature, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

        $user_alias = MODEL_ALIAS[$this->staff_model->TableName()];
        $s_alias    = MODEL_ALIAS[$this->enterprise_model->TableName()];

        $join = [
            [
                'left',
                $this->enterprise_model->TableName() . ' as ' . $s_alias,
                [
                    'and'   => ['a.id', '=', $s_alias . '.address_id'],
                    'where' => $s_alias . '.is_del = 1',
                ],
            ],
            [
                'left',
                $this->staff_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.enterprise_id', '=', $s_alias . '.id'],
                ],
            ]
        ];

        $res = $this->area_model->GetList($filed, $where, $join, '', 'a.id', $is_page, $per_page);

        return $res;
    }

    /**
     * 总计
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function Total()
    {
        $where = array_filter([
            'is_del' => 1
        ]);

        $filed = "count(f.id or null) total, count(f.is_contact=1 or null) as contact_num, count(f.temperature!='正常' or null) as temperature, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

        $user_alias = MODEL_ALIAS[$this->staff_model->TableName()];

        $join = [
            [
                'left',
                $this->staff_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.enterprise_id', '=', 'a.id'],
                ],
            ]
        ];

        $res = $this->enterprise_model->GetOne($filed, $where, $join);

        return $res;
    }

    /**
     * 企业
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function Enterprise1($params)
    {
        $where = array_filter([
            'id' => $params['id'],
            'is_del'          => 1
        ]);

        $filed = "a.id, a.enterprise_name, count(f.id or null) total, count(f.is_contact=1 or null) as contact_num, count(f.temperature!='正常' or null) as temperature, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

        $user_alias = MODEL_ALIAS[$this->staff_model->TableName()];

        $join = [
            [
                'left',
                $this->staff_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.enterprise_id', '=', 'a.id'],
                ],
            ]
        ];

        $res = $this->enterprise_model->GetOne($filed, $where, $join);

        return $res;
    }
}