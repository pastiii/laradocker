<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/14
 * Time: 17:41
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\ApplyModel;
use App\Models\ApplySaveModel;
use App\Models\ApprovalModel;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;

class ApplyService extends BaseService
{
    use ResponseTrait;

    /** @var ApplyModel */
    protected $apply_model;

    /** @var ApprovalModel */
    protected $approval_model;

    /** @var ApplySaveModel */
    protected $save_model;

    public function __construct()
    {
        parent::__construct();
        $this->apply_model    = new ApplyModel();
        $this->approval_model = new ApprovalModel();
        $this->save_model     = new ApplySaveModel();
    }

    /**
     * 提交复审申请
     * @param $params
     * @param int $type
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function CreateApply($params, $type = 1)
    {
        #校验企业是否存在
        $user_data = $this->apply_model->GetOne('a.*', ['c_user_id' => $this->user_id, 'state' => [1, 2, 3, 4]]);

        if ($user_data['status'] == RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[APPLY_SAME], APPLY_SAME);
        }

        if ($params['enterprise_id'] != 0) {
            $ret = $this->apply_model->GetOne('a.*', ['enterprise_id' => $params['enterprise_id'], 'state' => [1, 2, 3, 4]]);

            if ($ret['status'] == RETURN_SUCCESS) {
                throw new ExampleException(CHINESE_MSG[APPLY_ERROR], APPLY_ERROR);
            }
        }

        #后台提交申请
        $data = [];
        DB::beginTransaction();
        try {
            if ($type == 1) {
                if ($params['enterprise_id'] == 0) {
                    #添加企业
                    $res = (new EnterpriseService())->CreateEnterprise($params, 5);

                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $res['data']['id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'ctime'                  => time(),
                        'c_user_id'              => $this->user_id,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                } else {
                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $params['enterprise_id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'ctime'                  => time(),
                        'c_user_id'              => $this->user_id,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                }
            } elseif ($type == 2) {
                $data = [
                    'enterprise_name'        => $params['enterprise_name'],
                    'juridical_person'       => $params['juridical_person'],
                    'contacts'               => $params['contacts'],
                    'juridical_person_phone' => $params['juridical_person_phone'],
                    'address_id'             => $params['address_id'],
                    'enterprise_id'          => $params['enterprise_id'],
                    'address'                => $params['address'],
                    'credit_code'            => $params['credit_code'],
                    'business_scope'         => $params['business_scope'],
                    'start_time'             => $params['start_time'],
                    'staff_num'              => $params['staff_num'],
                    'return_num'             => $params['return_num'],
                    'not_return_num'         => $params['not_return_num'],
                    'six_category'           => $params['six_category'],
                    'isolation_room'         => $params['isolation_room'],
                    'is_disinfect'           => $params['is_disinfect'],
                    'measure_temperature'    => $params['measure_temperature'],
                    'is_propagate'           => $params['is_propagate'],
                    'application_reason'     => $params['application_reason'],
                    'state'                  => 1,
                    'ctime'                  => time(),
                    'c_user_id'              => $this->user_id,
                    'utime'                  => time(),
                    'u_user_id'              => $this->user_id,
                ];
            }

            $result = $this->apply_model->AddData($data);

            $where = [
                'c_user_id' => $this->user_id
            ];

            $ret = $this->save_model->GetOne('id', $where);

            if ($ret['status'] == RETURN_SUCCESS) {
                #编辑
                unset($data['state']);
                unset($data['ctime']);
                unset($data['c_user_id']);

                $save_where = [
                    'id' => $ret['data']['id']
                ];

                $this->save_model->EditData($save_where, $data);

            } else {
                #新增
                unset($data['state']);
                $this->save_model->AddData($data);
            }

            if ($type == 1) {
                #添加审批信息
                $code          = $this->GetCode();
                $approval_data = [
                    [
                        'code'      => $code,
                        'apply_id'  => $result->id,
                        'type'      => 1,
                        'state'     => 1,
                        'ctime'     => time(),
                        'c_user_id' => $this->user_id,
                        'utime'     => time(),
                        'u_user_id' => $this->user_id,
                    ],
                    [
                        'code'      => $code,
                        'apply_id'  => $result->id,
                        'type'      => 2,
                        'state'     => 0,
                        'ctime'     => time(),
                        'c_user_id' => $this->user_id,
                        'utime'     => time(),
                        'u_user_id' => $this->user_id,
                    ],
                    [
                        'code'      => $code,
                        'apply_id'  => $result->id,
                        'type'      => 3,
                        'state'     => 0,
                        'ctime'     => time(),
                        'c_user_id' => $this->user_id,
                        'utime'     => time(),
                        'u_user_id' => $this->user_id,
                    ],
                ];

                $this->approval_model->AddAll($approval_data);
            }

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }

        return $this->HandleData('add', $result);
    }

    /**
     * 编辑复工申请
     * @param $params
     * @param int $type
     * @return array
     * @throws ExampleException
     */
    public function EditApply($params, $type = 1)
    {
        #校验企业是否存在
        if ($params['enterprise_id'] != 0) {
            $ret = $this->apply_model->GetOne('a.*', ['enterprise_id' => $params['enterprise_id'], 'state' => [1, 2, 3, 4]]);

            if ($ret['status'] == RETURN_SUCCESS && $ret['data']['c_user_id'] != $this->user_id) {
                throw new ExampleException(CHINESE_MSG[APPLY_ERROR], APPLY_ERROR);
            }
        }

        #校验状态
        $ret = $this->apply_model->GetOne('a.*', ['enterprise_id' => $params['enterprise_id'], 'state' => [3, 4]]);

        if ($ret['status'] == RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[APPLY_ERROR_T], APPLY_ERROR_T);
        }

        #检测是否已存在审批信息
        $approval = $this->approval_model->GetOne('a.*', ['apply_id' => $params['id']]);


        #后台提交申请
        $where = [
            'id' => $params['id']
        ];

        $data = [];
        DB::beginTransaction();
        try {
            if ($type == 1) {
                if ($params['enterprise_id'] == 0) {
                    #添加企业
                    $res = (new EnterpriseService())->CreateEnterprise($params, 5);

                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $res['data']['id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                } else {
                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $params['enterprise_id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                }
            } elseif ($type == 2) {
                $data = [
                    'enterprise_name'        => $params['enterprise_name'],
                    'juridical_person'       => $params['juridical_person'],
                    'contacts'               => $params['contacts'],
                    'juridical_person_phone' => $params['juridical_person_phone'],
                    'address_id'             => $params['address_id'],
                    'enterprise_id'          => $params['enterprise_id'],
                    'address'                => $params['address'],
                    'credit_code'            => $params['credit_code'],
                    'business_scope'         => $params['business_scope'],
                    'start_time'             => $params['start_time'],
                    'staff_num'              => $params['staff_num'],
                    'return_num'             => $params['return_num'],
                    'not_return_num'         => $params['not_return_num'],
                    'six_category'           => $params['six_category'],
                    'isolation_room'         => $params['isolation_room'],
                    'is_disinfect'           => $params['is_disinfect'],
                    'measure_temperature'    => $params['measure_temperature'],
                    'is_propagate'           => $params['is_propagate'],
                    'application_reason'     => $params['application_reason'],
                    'utime'                  => time(),
                    'u_user_id'              => $this->user_id,
                ];
            }

            $result = $this->apply_model->EditData($where, $data);

            $where = [
                'c_user_id' => $this->user_id
            ];

            $ret = $this->save_model->GetOne('id', $where);

            if ($ret['status'] == RETURN_SUCCESS) {
                #编辑
                unset($data['state']);

                $save_where = [
                    'id' => $ret['data']['id']
                ];

                $this->save_model->EditData($save_where, $data);

            } else {
                #新增
                unset($data['state']);
                $data['ctime']     = time();
                $data['c_user_id'] = $this->user_id;
                $this->save_model->AddData($data);
            }


            if ($approval['status'] != RETURN_SUCCESS) {
                if ($type == 1) {
                    #添加审批信息
                    $code          = $this->GetCode();
                    $approval_data = [
                        [
                            'code'      => $code,
                            'apply_id'  => $params['id'],
                            'type'      => 1,
                            'state'     => 1,
                            'ctime'     => time(),
                            'c_user_id' => $this->user_id,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ],
                        [
                            'code'      => $code,
                            'apply_id'  => $params['id'],
                            'type'      => 2,
                            'state'     => 0,
                            'ctime'     => time(),
                            'c_user_id' => $this->user_id,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ],
                        [
                            'code'      => $code,
                            'apply_id'  => $params['id'],
                            'type'      => 3,
                            'state'     => 0,
                            'ctime'     => time(),
                            'c_user_id' => $this->user_id,
                            'utime'     => time(),
                            'u_user_id' => $this->user_id,
                        ],
                    ];

                    $this->approval_model->AddAll($approval_data);
                }
            }

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }

        return $this->HandleData('edit', $result);
    }

    /**
     * 复工详情
     * @return array
     * @throws ExampleException
     */
    public function ApplyDetail()
    {
        $filed = 'id, enterprise_name, enterprise_id, juridical_person, contacts, juridical_person_phone, credit_code, 
        address_id, address, business_scope, FROM_UNIXTIME(start_time, "%Y-%m-%d %H:%i:%s") as start_time, staff_num, return_num, not_return_num, six_category, 
        isolation_room, is_disinfect, measure_temperature, is_propagate, application_reason, file_one, file_two, 
        file_three, file_one_name, file_two_name, file_three_name, state';

        $where = [
            'c_user_id' => $this->user_id,
        ];

        $result = $this->apply_model->GetOne($filed, $where);

        #获取审批code
        if ($result['status'] == RETURN_SUCCESS) {
            $res = $this->approval_model->GetOne('a.*', ['apply_id' => $result['data']['id']]);

            if ($res['status'] == RETURN_SUCCESS) {
                $result['data']['code'] = $res['data']['code'];
            } else {
                $result['data']['code'] = NULL;
            }
        }

        return $result;
    }

    /**
     * 获取code
     * @return string
     */
    public function GetCode()
    {
        $str = date('Ymd') . rand(10000, 99999);

        $res = $this->approval_model->GetCode(['code' => $str]);

        if (!$res) {
            return $str;
        } else {
            return $this->GetCode();
        }
    }

    /**
     * 初始化
     * @param $params
     * @param $type
     * @return array
     * @throws ExampleException
     */
    public function ApplyInit($params, $type = 1)
    {
        #校验企业是否存在
        if ($params['enterprise_id'] != 0) {
            $ret = $this->apply_model->GetOne('a.*', ['enterprise_id' => $params['enterprise_id'], 'state' => [1, 2, 3, 4]]);

            if ($ret['status'] == RETURN_SUCCESS && $ret['data']['c_user_id'] != $this->user_id) {
                throw new ExampleException(CHINESE_MSG[APPLY_ERROR], APPLY_ERROR);
            }
        }

        #校验状态
        $ret = $this->apply_model->GetOne('a.*', ['enterprise_id' => $params['enterprise_id'], 'state' => [3, 4]]);

        if ($ret['status'] == RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[APPLY_ERROR_T], APPLY_ERROR_T);
        }

        #检测状态
        $where = [
            'id'    => $params['id'],
            'state' => [5, 6],
        ];

        $res = $this->apply_model->GetOne('a.*', $where);
        if ($res['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);
        }

        #后台提交申请
        $where = [
            'id' => $params['id']
        ];

        DB::beginTransaction();
        try {
            if ($type == 1) {
                if ($params['enterprise_id'] == 0) {
                    #添加企业
                    $res = (new EnterpriseService())->CreateEnterprise($params, 5);

                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $res['data']['id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                } else {
                    $data = [
                        'enterprise_name'        => $params['enterprise_name'],
                        'juridical_person'       => $params['juridical_person'],
                        'contacts'               => $params['contacts'],
                        'juridical_person_phone' => $params['juridical_person_phone'],
                        'address_id'             => $params['address_id'],
                        'enterprise_id'          => $params['enterprise_id'],
                        'address'                => $params['address'],
                        'credit_code'            => $params['credit_code'],
                        'business_scope'         => $params['business_scope'],
                        'start_time'             => $params['start_time'],
                        'staff_num'              => $params['staff_num'],
                        'return_num'             => $params['return_num'],
                        'not_return_num'         => $params['not_return_num'],
                        'six_category'           => $params['six_category'],
                        'isolation_room'         => $params['isolation_room'],
                        'is_disinfect'           => $params['is_disinfect'],
                        'measure_temperature'    => $params['measure_temperature'],
                        'is_propagate'           => $params['is_propagate'],
                        'application_reason'     => $params['application_reason'],
                        'file_one'               => $params['file_one'],
                        'file_two'               => $params['file_two'],
                        'file_three'             => $params['file_three'],
                        'file_three_name'        => $params['file_three_name'],
                        'file_two_name'          => $params['file_two_name'],
                        'file_one_name'          => $params['file_one_name'],
                        'state'                  => 2,
                        'utime'                  => time(),
                        'u_user_id'              => $this->user_id,
                    ];
                }
            } elseif ($type == 2) {
                $data = [
                    'enterprise_name'        => $params['enterprise_name'],
                    'juridical_person'       => $params['juridical_person'],
                    'contacts'               => $params['contacts'],
                    'juridical_person_phone' => $params['juridical_person_phone'],
                    'address_id'             => $params['address_id'],
                    'enterprise_id'          => $params['enterprise_id'],
                    'address'                => $params['address'],
                    'credit_code'            => $params['credit_code'],
                    'business_scope'         => $params['business_scope'],
                    'start_time'             => $params['start_time'],
                    'staff_num'              => $params['staff_num'],
                    'return_num'             => $params['return_num'],
                    'not_return_num'         => $params['not_return_num'],
                    'six_category'           => $params['six_category'],
                    'isolation_room'         => $params['isolation_room'],
                    'is_disinfect'           => $params['is_disinfect'],
                    'measure_temperature'    => $params['measure_temperature'],
                    'is_propagate'           => $params['is_propagate'],
                    'application_reason'     => $params['application_reason'],
                    'state'                  => 1,
                    'utime'                  => time(),
                    'u_user_id'              => $this->user_id,
                ];
            }

            $result = $this->apply_model->EditData($where, $data);

            $where = [
                'c_user_id' => $this->user_id
            ];

            $ret = $this->save_model->GetOne('id', $where);

            if ($ret['status'] == RETURN_SUCCESS) {
                #编辑
                unset($data['state']);

                $save_where = [
                    'id' => $ret['data']['id']
                ];

                $this->save_model->EditData($save_where, $data);

            } else {
                #新增
                unset($data['state']);
                $this->save_model->AddData($data);
            }

            #初始化审批信息
            if ($type == 1) {
                $where = [
                    'apply_id' => $params['id'],
                    'type'     => 1
                ];

                $where1 = [
                    'apply_id' => $params['id'],
                    'type'     => 2
                ];

                $where2 = [
                    'apply_id' => $params['id'],
                    'type'     => 3
                ];


                $approval_data = [
                    'type'      => 1,
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'state'     => 1,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];


                $approval_data1 = [
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'type'      => 2,
                    'state'     => 0,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];


                $approval_data2 = [
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'type'      => 3,
                    'state'     => 0,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];
            } else {
                $where = [
                    'apply_id' => $params['id'],
                    'type'     => 1
                ];

                $where1 = [
                    'apply_id' => $params['id'],
                    'type'     => 2
                ];

                $where2 = [
                    'apply_id' => $params['id'],
                    'type'     => 3
                ];


                $approval_data = [
                    'apply_id'  => 0,
                    'type'      => 1,
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'state'     => 1,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];


                $approval_data1 = [
                    'apply_id'  => 0,
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'type'      => 2,
                    'state'     => 0,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];


                $approval_data2 = [
                    'apply_id'  => 0,
                    'opinion'   => NULL,
                    'remark'    => NULL,
                    'type'      => 3,
                    'state'     => 0,
                    'utime'     => time(),
                    'u_user_id' => $this->user_id,
                ];
            }


            $this->approval_model->EditData($where, $approval_data);
            $this->approval_model->EditData($where1, $approval_data1);
            $this->approval_model->EditData($where2, $approval_data2);

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }

        return $this->HandleData('edit', $result);
    }

    /**
     * 保存
     * @param $params
     * @param $type
     * @return array
     * @throws ExampleException
     */
    public function SaveApply($params, $type = 1)
    {
        if ($params['id'] != 0) {
            $where = [
                'id' => $params['id']
            ];

            $data = [
                'enterprise_name'        => $params['enterprise_name'] ?? NULL,
                'juridical_person'       => $params['juridical_person'] ?? NULL,
                'contacts'               => $params['contacts'] ?? NULL,
                'juridical_person_phone' => $params['juridical_person_phone'] ?? NULL,
                'address_id'             => $params['address_id'] ?? 0,
                'enterprise_id'          => $params['enterprise_id'] ?? 0,
                'address'                => $params['address'] ?? NULL,
                'credit_code'            => $params['credit_code'] ?? NULL,
                'business_scope'         => $params['business_scope'] ?? NULL,
                'start_time'             => $params['start_time'] ?? NULL,
                'staff_num'              => $params['staff_num'] ?? NULL,
                'return_num'             => $params['return_num'] ?? NULL,
                'not_return_num'         => $params['not_return_num'] ?? NULL,
                'six_category'           => $params['six_category'] ?? NULL,
                'isolation_room'         => $params['isolation_room'] ?? 0,
                'is_disinfect'           => $params['is_disinfect'] ?? 0,
                'measure_temperature'    => $params['measure_temperature'] ?? 0,
                'is_propagate'           => $params['is_propagate'] ?? 0,
                'application_reason'     => $params['application_reason'] ?? NULL,
                'file_one'               => $params['file_one'] ?? NULL,
                'file_two'               => $params['file_two'] ?? NULL,
                'file_three'             => $params['file_three'] ?? NULL,
                'file_three_name'        => $params['file_three_name'] ?? NULL,
                'file_two_name'          => $params['file_two_name'] ?? NULL,
                'file_one_name'          => $params['file_one_name'] ?? NULL,
                'utime'                  => time(),
                'u_user_id'              => $this->user_id,
            ];

            if ($type == 2) {
                unset($data['file_one']);
                unset($data['file_two']);
                unset($data['file_three']);
                unset($data['file_three_name']);
                unset($data['file_two_name']);
                unset($data['file_one_name']);
            }

            #编辑
            $res = $this->save_model->EditData($where, $data);

            return $this->HandleData('edit', $res);

        } else {
            $where = [
                'c_user_id' => $this->user_id
            ];

            $ret = $this->save_model->GetOne('id', $where);

            if ($ret['status'] == RETURN_SUCCESS) {
                throw new ExampleException(CHINESE_MSG[APPLY_ERROR_S], APPLY_ERROR_S);
            }

            #新增
            $data = [
                'enterprise_name'        => $params['enterprise_name'] ?? NULL,
                'juridical_person'       => $params['juridical_person'] ?? NULL,
                'contacts'               => $params['contacts'] ?? NULL,
                'juridical_person_phone' => $params['juridical_person_phone'] ?? NULL,
                'address_id'             => $params['address_id'] ?? 0,
                'enterprise_id'          => $params['enterprise_id'] ?? 0,
                'address'                => $params['address'] ?? NULL,
                'credit_code'            => $params['credit_code'] ?? NULL,
                'business_scope'         => $params['business_scope'] ?? NULL,
                'start_time'             => $params['start_time'] ?? NULL,
                'staff_num'              => $params['staff_num'] ?? NULL,
                'return_num'             => $params['return_num'] ?? NULL,
                'not_return_num'         => $params['not_return_num'] ?? NULL,
                'six_category'           => $params['six_category'] ?? NULL,
                'isolation_room'         => $params['isolation_room'] ?? 0,
                'is_disinfect'           => $params['is_disinfect'] ?? 0,
                'measure_temperature'    => $params['measure_temperature'] ?? 0,
                'is_propagate'           => $params['is_propagate'] ?? 0,
                'application_reason'     => $params['application_reason'] ?? NULL,
                'file_one'               => $params['file_one'] ?? NULL,
                'file_two'               => $params['file_two'] ?? NULL,
                'file_three'             => $params['file_three'] ?? NULL,
                'file_three_name'        => $params['file_three_name'] ?? NULL,
                'file_two_name'          => $params['file_two_name'] ?? NULL,
                'file_one_name'          => $params['file_one_name'] ?? NULL,
                'ctime'                  => time(),
                'c_user_id'              => $this->user_id,
                'utime'                  => time(),
                'u_user_id'              => $this->user_id,
            ];

            if ($type == 2) {
                unset($data['file_one']);
                unset($data['file_two']);
                unset($data['file_three']);
                unset($data['file_three_name']);
                unset($data['file_two_name']);
                unset($data['file_one_name']);
            }

            $res = $this->save_model->AddData($data);

            return $this->HandleData('add', $res);
        }
    }

    /**
     * 获取储存数据
     * @return array
     * @throws ExampleException
     */
    public function GetSave()
    {
        $filed = 'id, enterprise_name, enterprise_id, juridical_person, contacts, juridical_person_phone, credit_code, 
        address_id, address, business_scope, start_time, staff_num, return_num, not_return_num, six_category, 
        isolation_room, is_disinfect, measure_temperature, is_propagate, application_reason, file_one, file_two, 
        file_three, file_one_name, file_two_name, file_three_name';

        $where = [
            'c_user_id' => $this->user_id,
        ];

        $result = $this->save_model->GetOne($filed, $where);

        return $result;
    }
}
