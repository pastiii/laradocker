<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/15
 * Time: 15:34
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\ApplyModel;
use App\Models\ApprovalModel;
use App\Models\PhoneModel;
use App\Models\UserModel;
use App\Traits\HtmlTraits;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class ApprovalService extends BaseService
{
    use ResponseTrait, HtmlTraits;

    /** @var ApplyModel */
    protected $apply_model;

    /** @var ApprovalModel */
    protected $approval_model;

    /** @var PhoneModel */
    protected $phone_model;

    /** @var UserModel */
    protected $u_model;

    public function __construct()
    {
        parent::__construct();
        $this->apply_model    = new ApplyModel();
        $this->approval_model = new ApprovalModel();
        $this->phone_model    = new PhoneModel();
        $this->u_model        = new UserModel();
    }

    /**
     * 审批列表
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function ApprovalList($params, $is_page, $per_page)
    {
        $where = array_filter([
            'code_like'       => $params['code'] ?? '',
            'enterprise_name' => $params['enterprise_name'] ?? '',
            'state'           => empty($params['state']) ? [1, 3] : [$params['state']],
            'type'            => $params['type'] ?? '',
        ]);

        if (empty($params['state']) && $params['type'] == 3) {
            $where['state'] = [1,2,3,4];
        }

        $filed = 'a.id, a.code, p.phone, apply.enterprise_name, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime, FROM_UNIXTIME(a.utime, "%Y-%m-%d %H:%i:%s") as utime, u.user_name, a.state';

        $user_alias  = MODEL_ALIAS[$this->apply_model->TableName()];
        $token_alias = MODEL_ALIAS[$this->u_model->TableName()];
        $t_alias = MODEL_ALIAS[$this->phone_model->TableName()];

        $join = [
            [
                'right',
                $this->apply_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.id', '=', 'a.apply_id'],
                ],
            ],
            [
                'left',
                $this->u_model->TableName() . ' as ' . $token_alias,
                [
                    'and' => [$token_alias . '.id', '=', 'a.u_user_id'],
                ],
            ],

            [
                'left',
                $this->phone_model->TableName() . ' as ' . $t_alias,
                [
                    'and' => [$t_alias . '.id', '=', 'a.c_user_id'],
                ],
            ],
        ];

        $res = $this->approval_model->GetList($filed, $where, $join, '', 'a.id', $is_page, $per_page);

        return $res;
    }

    /**
     * 审批详情
     * @param $params
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function ApprovalDetail($params)
    {
        $where = [
            'id' => $params['id']
        ];

        $filed = 'a.id, a.code, p.phone, a.opinion, a.remark, a.state, a.apply_id, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime, FROM_UNIXTIME(a.utime, "%Y-%m-%d %H:%i:%s") as utime, apply.enterprise_name, apply.enterprise_id, apply.juridical_person, apply.contacts, apply.juridical_person_phone, apply.credit_code, 
        apply.address, apply.business_scope, FROM_UNIXTIME(apply.start_time, "%Y-%m-%d %H:%i:%s") as start_time, apply.staff_num, apply.return_num, apply.not_return_num, apply.six_category, 
        apply.isolation_room, apply.is_disinfect, apply.measure_temperature, apply.is_propagate, apply.application_reason, apply.file_one, apply.file_two, 
        apply.file_three, apply.file_one_name, apply.file_two_name, apply.file_three_name';

        $user_alias = MODEL_ALIAS[$this->apply_model->TableName()];

        $token_alias = MODEL_ALIAS[$this->phone_model->TableName()];

        $join = [
            [
                'left',
                $this->apply_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.id', '=', 'a.apply_id'],
                ],
            ],
            [
                'left',
                $this->phone_model->TableName() . ' as ' . $token_alias,
                [
                    'and' => [$token_alias . '.id', '=', 'a.c_user_id'],
                ],
            ],
        ];

        $res = $this->approval_model->GetOne($filed, $where, $join);

        return $res;
    }

    /**
     * 审批
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function Approval($params)
    {
        $where = [
            'id' => $params['id']
        ];

        $set = array_filter([
            'state'     => $params['state'],
            'opinion'   => $params['opinion'],
            'remark'    => $params['remark'] ?? '',
            'utime'     => time(),
            'u_user_id' => $this->user_id,
        ]);

        $approval_info = $this->approval_model->GetOne('a.*', $where);

        if ($approval_info['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);
        }

        DB::beginTransaction();
        try {
            switch ($params['type']) {
                case 1:
                    $result = $this->approval_model->EditData($where, $set);

                    if ($params['state'] == 2) {
                        #显示下级
                        $s_where = [
                            'apply_id' => $approval_info['data']['apply_id'],
                            'type'     => 2
                        ];

                        $s_set = [
                            'state'     => 1,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ];

                        $this->approval_model->EditData($s_where, $s_set);
                    }

                    #修改申请状态
                    $a_where = [
                        'id' => $approval_info['data']['apply_id']
                    ];

                    $a_set = [
                        'state'     => $params['state'] == 2 ? 3 : 5,
                        'utime'     => time(),
                        'u_user_id' => $this->user_id,
                    ];


                    $this->apply_model->EditData($a_where, $a_set);
                    break;
                case 2:
                    $result = $this->approval_model->EditData($where, $set);

                    if ($params['state'] == 2) {
                        #显示下级
                        $s_where = [
                            'apply_id' => $approval_info['data']['apply_id'],
                            'type'     => 3
                        ];

                        $s_set = [
                            'state'     => 1,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ];

                        $this->approval_model->EditData($s_where, $s_set);
                    }

                    if ($params['state'] == 3) {
                        #修改申请状态
                        $a_where = [
                            'id' => $approval_info['data']['apply_id']
                        ];

                        $a_set = [
                            'state'     => $params['state'] == 2 ? 3 : 5,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ];


                        $this->apply_model->EditData($a_where, $a_set);
                    }

                    break;
                case 3:
                    $result = $this->approval_model->EditData($where, $set);

                    #修改申请状态
                    $a_where = [
                        'id' => $approval_info['data']['apply_id']
                    ];

                    $a_set = [
                        'state'     => $params['state'] == 2 ? 4 : 5,
                        'utime'     => time(),
                        'u_user_id' => $this->user_id,
                    ];


                    $this->apply_model->EditData($a_where, $a_set);
                    break;
            }

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }

        return $this->HandleData('edit', $result);
    }

    /**
     * 审批流程
     * @param $params
     * @param int $type
     * @return array
     * @throws ExampleException
     */
    public function ApprovalProcess($params, $type = 1)
    {
        $where = [
            'apply_id' => $params['id'],
            'state' => [2,3,4]
        ];

        $filed = 'a.id, a.type, a.opinion, a.remark, FROM_UNIXTIME(a.utime, "%Y-%m-%d %H:%i:%s") as utime, u.user_name, a.state';

        $token_alias = MODEL_ALIAS[$this->u_model->TableName()];

        $join = [
            [
                'left',
                $this->u_model->TableName() . ' as ' . $token_alias,
                [
                    'and' => [$token_alias . '.id', '=', 'a.u_user_id'],
                ],
            ],
        ];

        $res = $this->approval_model->GetList($filed, $where, $join, 'a.id asc', 'a.id');

        if ($type != 1) {
            $apply_data = $this->apply_model->Getlist('FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as utime', ['id' => $params['id']]);

            #组织代码
            if ($apply_data['status'] == RETURN_SUCCESS && $res['status'] == RETURN_SUCCESS) {
                $data = array_merge($apply_data['data'], $res['data']);
                return $this->HandleData('other', $data);
            }else{
                return $apply_data;
            }
        }

        return $res;
    }

    /**
     * 复产复工统计列表
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws ExampleException
     */
    public function ApplyList($params, $is_page, $per_page)
    {
        $filed = 'id, enterprise_name, contacts, juridical_person_phone, FROM_UNIXTIME(start_time, "%Y-%m-%d %H:%i:%s") as start_time, staff_num, return_num, not_return_num, six_category, 
        isolation_room, is_disinfect, measure_temperature, is_propagate, state';

        $where = array_filter(
            [
                'enterprise_name' => $params['enterprise_name'] ?? ''
            ]
        );

        $res = $this->apply_model->GetList($filed, $where, [], '', '', $is_page, $per_page);

        return $res;
    }

    /**
     * pdf
     * @param $params
     * @throws ExampleException
     * @throws \Mpdf\MpdfException
     */
    public function PdfFile($params)
    {
        #获取参数
        $where = [
            'id' => $params['id']
        ];

        $data = $this->approval_model->GetOne('a.*', $where);

        if ($data['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[RETURN_DATA_EMPTY], RETURN_DATA_EMPTY);
        }

        $all_where = [
            'apply_id' => $data['data']['apply_id']
        ];

        $apply_alias = MODEL_ALIAS[$this->apply_model->TableName()];
        $token_alias = MODEL_ALIAS[$this->u_model->TableName()];

        $join = [
            [
                'left',
                $this->apply_model->TableName() . ' as ' . $apply_alias,
                [
                    'and' => [$apply_alias . '.id', '=', 'a.apply_id'],
                ],
            ],
            [
                'left',
                $this->u_model->TableName() . ' as ' . $token_alias,
                [
                    'and' => [$token_alias . '.id', '=', 'a.u_user_id'],
                ],
            ],
        ];

        $approval = $this->approval_model->GetList('a.*, apply.enterprise_name, u.user_name', $all_where, $join, 'a.type asc');

        if ($approval['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[RETURN_DATA_EMPTY], RETURN_DATA_EMPTY);
        }


        $pdf_data = [];
        foreach ($approval['data'] as $val) {
            switch ($val['type']) {
                case 1:
                    $pdf_data['enterprise_name'] = $val['enterprise_name'];
                    $pdf_data['opinion1'] = $val['opinion'];
                    $pdf_data['str1'] = "签字: ".$val['user_name'].'  '.date("Y", $val['utime'])."年".date("m", $val['utime'])."月".date("d", $val['utime'])."日";
                    $pdf_data['remark'] = $val['remark'];
                    break;
                case 2:
                    $pdf_data['opinion2'] = $val['opinion'];
                    $pdf_data['str2'] = "签字: ".$val['user_name'].'  '.date("Y", $val['utime'])."年".date("m", $val['utime'])."月".date("d", $val['utime'])."日";
                    $pdf_data['remark'] = $val['remark'] == '' ? $pdf_data['remark'] : $val['remark'];
                    break;
                case 3:
                    $pdf_data['opinion3'] = $val['opinion'];
                    $pdf_data['str3'] = "签字: ".$val['user_name'].'  '.date("Y", $val['utime'])."年".date("m", $val['utime'])."月".date("d", $val['utime'])."日";
                    $pdf_data['remark'] = $val['remark'] == '' ? $pdf_data['remark'] : $val['remark'];
                    break;

            }
        }

        #输出pdf
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font_size' => '16',
        ]);

        $html = $this->GetHtml($pdf_data);

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;
        $mpdf->WriteHTML($html, 0);
        $mpdf->Output('approval.pdf', 'D');

    }
}
