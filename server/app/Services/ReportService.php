<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/9
 * Time: 18:40
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\AreaModel;
use App\Models\AreaReportModel;
use App\Models\EnterpriseModel;
use App\Models\ReportModel;
use App\Models\StaffModel;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class ReportService extends BaseService
{
    use ResponseTrait;

    /** @var StaffModel */
    protected $staff_model;

    /** @var EnterpriseModel */
    protected $enterprise_model;

    /** @var AreaModel */
    protected $area_model;

    /** @var AreaReportModel */
    protected $area_report_model;

    /** @var ReportModel */
    protected $report_model;

    public function __construct()
    {
        parent::__construct();
        $this->staff_model       = new StaffModel();
        $this->enterprise_model  = new EnterpriseModel();
        $this->area_model        = new AreaModel();
        $this->area_report_model = new AreaReportModel();
        $this->report_model      = new ReportModel();
    }

    public function Region()
    {
        #初始化地区数据
        $where = [
            'address_id_in' => LAOTING
        ];

        $res = $this->area_report_model->GetCount($where);

        if ($res < 1) {
            $data = [];

            foreach (LAOTING as $value) {
                $param = [
                    'address_id'  => $value,
                    'contact_num' => 0,
                    'routine'     => 0,
                    'suspected'   => 0,
                    'diagnosis'   => 0,
                ];

                array_push($data, $param);
            }

            $this->area_report_model->AddAll($data);
        }

        #获取区域数据
        $where['id_in'] = LAOTING;

        $filed = "area_r.id, a.township, area_r.contact_num as report_contact_num, area_r.diagnosis as report_diagnosis, area_r.routine as report_routine, area_r.suspected as report_suspected,count(f.is_contact=1 or null) as contact_num, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

        $user_alias = MODEL_ALIAS[$this->staff_model->TableName()];
        $s_alias    = MODEL_ALIAS[$this->enterprise_model->TableName()];
        $a_alias    = MODEL_ALIAS[$this->area_report_model->TableName()];

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
            ],
            [
                'left',
                $this->area_report_model->TableName() . ' as ' . $a_alias,
                [
                    'and' => [$a_alias . '.address_id', '=', 'a.id'],
                ],
            ]
        ];

        $res = $this->area_model->GetList($filed, $where, $join, '', 'a.id');

        return $res;

    }


    public function Overview()
    {
        #获取当前所有数据总数
        $where = array_filter([
            'is_del' => 1
        ]);

        $filed = "count(f.is_contact=1 or null) as contact_num, count(f.is_quarantine=1 or null) as suspected, count(f.is_quarantine=2 or null) as routine, count(f.is_diagnosis=1 or null) as diagnosis";

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

        if ($res['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[MYSQL_ERROR], MYSQL_ERROR);
        }

        #获取上次发布数据
        $filed_report = 'id, contact_num, routine, suspected, diagnosis, death, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime';
        $ret          = $this->report_model->GetInfo($filed_report);
        $len          = count($ret);

        if (!$ret) {
            $overview_data = [
                'contact_num' => 0,
                'routine'     => 0,
                'suspected'   => 0,
                'diagnosis'   => 0,
                'death'       => 0,
            ];

            $increment_data = [
                'contact_num' => 0,
                'routine'     => 0,
                'suspected'   => 0,
                'diagnosis'   => 0,
                'death'       => 0,
            ];
        } elseif ($len == 1) {
            $overview_data = [
                'contact_num' => $ret[0]['contact_num'],
                'routine'     => $ret[0]['routine'],
                'suspected'   => $ret[0]['suspected'],
                'diagnosis'   => $ret[0]['diagnosis'],
                'death'       => $ret[0]['death'],
            ];

            $increment_data = [
                'contact_num' => 0,
                'routine'     => 0,
                'suspected'   => 0,
                'diagnosis'   => 0,
                'death'       => 0,
            ];
        } else {
            #计算数值
            $overview_data = [
                'contact_num' => $ret[0]['contact_num'],
                'routine'     => $ret[0]['routine'],
                'suspected'   => $ret[0]['suspected'],
                'diagnosis'   => $ret[0]['diagnosis'],
                'death'       => $ret[0]['death'],
            ];

            $increment_data = [
                'contact_num' => $ret[0]['contact_num'] - $ret[1]['contact_num'],
                'routine'     => $ret[0]['routine'] - $ret[1]['routine'],
                'suspected'   => $ret[0]['suspected'] - $ret[1]['suspected'],
                'diagnosis'   => $ret[0]['diagnosis'] - $ret[1]['diagnosis'],
                'death'       => $ret[0]['death'] - $ret[1]['death'],
            ];
        }

        $result = [
            'total'     => $res['data'],
            'overview'  => $overview_data,
            'increment' => $increment_data,
        ];

        return $this->HandleData('other', $result);
    }

    public function Publishing($params)
    {
        #数据处理
        $add_data = [
            'contact_num' => $params['contact_num'],
            'routine'     => $params['routine'],
            'suspected'   => $params['suspected'],
            'diagnosis'   => $params['diagnosis'],
            'death'       => $params['death'],
            'ctime'       => time(),
            'c_user_id'   => $this->user_id
        ];

        if (empty($params['overview'])) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED_FAIL], RETURN_FILED_FAIL);
        }

        DB::beginTransaction();

        try {
            $res = $this->report_model->AddData($add_data);

            foreach ($params['overview'] as $v) {
                $where = [
                    'id' => $v['id']
                ];

                $set = [
                    'contact_num' => $v['contact_num'],
                    'routine'     => $v['routine'],
                    'suspected'   => $v['suspected'],
                    'diagnosis'   => $v['diagnosis'],
                ];

                $this->area_report_model->EditData($where, $set);
            }

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }

        return $this->HandleData('edit', $res);
    }

    public function AreaReport($is_page, $per_page)
    {
        #初始化地区数据
        if ($this->user_info['management_area'] != "All") {
            $where['id_in'] = explode(',', $this->user_info['management_area']);
        } elseif ($this->user_info['management_area'] == "All") {
            $where['id_in'] = LAOTING;
        }

        $a_alias    = MODEL_ALIAS[$this->area_report_model->TableName()];

        $filed = 'area_r.id, a.township, area_r.contact_num, area_r.routine, area_r.suspected, area_r.diagnosis';

        $join = [
            [
                'left',
                $this->area_report_model->TableName() . ' as ' . $a_alias,
                [
                    'and' => [$a_alias . '.address_id', '=', 'a.id'],
                ],
            ]
        ];

        $res = $this->area_model->GetList($filed, $where, $join, '', '', $is_page, $per_page);

        return $res;
    }

    public function TotalReport()
    {
        $filed = 'id, contact_num, routine, suspected, diagnosis, death, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime';
        $res = $this->report_model->GetOne($filed, [], [], 'a.id desc');

        if ($res['status'] != RETURN_SUCCESS) {
            $increment_data = [
                'contact_num' => 0,
                'routine'     => 0,
                'suspected'   => 0,
                'diagnosis'   => 0,
                'death'       => 0,
                'ctime'       => date('Y-m-d H:i:s', time())
            ];
            return $this->HandleData('other', $increment_data);
        }else{
            return $res;
        }

    }
}