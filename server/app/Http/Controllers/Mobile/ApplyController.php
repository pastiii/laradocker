<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Services\ApplyService;
use App\Services\ApprovalService;
use Illuminate\Http\Request;

class ApplyController extends BaseController
{
    /** @var  ApplyService */
    protected $apply_service;

    /**
     * 初始化数据
     * ApplyController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->apply_service = new ApplyService();
    }

    /** @var array 字段别名 */
    protected $custom = [
        'enterprise_name'        => '企业名称',
        'juridical_person'       => '法人名称',
        'juridical_person_phone' => '联系方式',
        'address_id'             => '所在地区',
        'address'                => '详细地址',
        'enterprise_id'          => '企业ID',
        'contacts'               => '联系人',
        'credit_code'            => '统一社会信用代码',
        'business_scope'         => '经营范围',
        'start_time'             => '企业复产时间',
        'staff_num'              => '企业用工人数',
        'return_num'             => '实际返岗人数',
        'not_return_num'         => '县外人数',
        'six_category'           => '六类人数',
        'isolation_room'         => '设置隔离室',
        'is_disinfect'           => '是否消毒防控',
        'measure_temperature'    => '是否进行体温检测',
        'is_propagate'           => '是否进行防疫宣传',
        'application_reason'     => '复产复工申请理由',
        'file_one'               => '责任承诺书',
        'file_two'               => '防恐应急预案',
        'file_three'             => '健康统计表',
    ];

    /**
     * 提交复工申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function CreateApply(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name'        => 'required|string|max:32',
            'juridical_person'       => 'required|max:10|string',
            'contacts'               => 'required|max:10|string',
            'juridical_person_phone' => 'required|regex:/^1\d{10}$/|string',
            'address_id'             => 'required|int',
            'enterprise_id'          => 'required|int',
            'address'                => 'required|string',
            'credit_code'            => 'required|string|max:18',
            'business_scope'         => 'required|string',
            'start_time'             => 'required|int',
            'staff_num'              => 'required|int',
            'return_num'             => 'required|int',
            'not_return_num'         => 'required|int',
            'six_category'           => 'required|int',
            'isolation_room'         => 'required|int',
            'is_disinfect'           => 'required|int',
            'measure_temperature'    => 'required|int',
            'is_propagate'           => 'required|int',
            'application_reason'     => 'required|string|max:500',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->apply_service->CreateApply($this->params, 2);

        return $this->Response($result);
    }

    /**
     * 编辑复工申请
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function EditApply(Request $request)
    {
        #校验规则
        $rule = [
            'id'                     => 'required|int',
            'enterprise_name'        => 'required|string|max:32',
            'juridical_person'       => 'required|max:10|string',
            'contacts'               => 'required|max:10|string',
            'juridical_person_phone' => 'required|regex:/^1\d{10}$/|string',
            'address_id'             => 'required|int',
            'enterprise_id'          => 'required|int',
            'address'                => 'required|string',
            'credit_code'            => 'required|string|max:18',
            'business_scope'         => 'required|string',
            'start_time'             => 'required|int',
            'staff_num'              => 'required|int',
            'return_num'             => 'required|int',
            'not_return_num'         => 'required|int',
            'six_category'           => 'required|int',
            'isolation_room'         => 'required|int',
            'is_disinfect'           => 'required|int',
            'measure_temperature'    => 'required|int',
            'is_propagate'           => 'required|int',
            'application_reason'     => 'required|string|max:500',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->apply_service->EditApply($this->params, 2);

        return $this->Response($result);
    }

    /**
     * 复工详情
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function ApplyDetail()
    {
        $result = $this->apply_service->ApplyDetail();

        return $this->Response($result);
    }

    /**
     * 审批流程
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ApprovalProcess(Request $request)
    {
        $rule = [
            'id' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = (new ApprovalService())->ApprovalProcess($this->params, 2);

        return $this->Response($result);
    }

    /**
     * 初始化
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ApplyInit(Request $request)
    {
        #校验规则
        $rule = [
            'id'                     => 'required|int',
            'enterprise_name'        => 'required|string|max:32',
            'juridical_person'       => 'required|max:10|string',
            'contacts'               => 'required|max:10|string',
            'juridical_person_phone' => 'required|regex:/^1\d{10}$/|string',
            'address_id'             => 'required|int',
            'enterprise_id'          => 'required|int',
            'address'                => 'required|string',
            'credit_code'            => 'required|string|max:18',
            'business_scope'         => 'required|string',
            'start_time'             => 'required|int',
            'staff_num'              => 'required|int',
            'return_num'             => 'required|int',
            'not_return_num'         => 'required|int',
            'six_category'           => 'required|int',
            'isolation_room'         => 'required|int',
            'is_disinfect'           => 'required|int',
            'measure_temperature'    => 'required|int',
            'is_propagate'           => 'required|int',
            'application_reason'     => 'required|string|max:500',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->apply_service->ApplyInit($this->params, 2);

        return $this->Response($result);
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function SaveApply(Request $request)
    {
        #校验规则
        $rule = [
            'id'                     => 'nullable|int',
            'enterprise_name'        => 'nullable|string|max:32',
            'juridical_person'       => 'nullable|max:10|string',
            'contacts'               => 'nullable|max:10|string',
            'juridical_person_phone' => 'nullable|regex:/^1\d{10}$/|string',
            'address_id'             => 'nullable|int',
            'enterprise_id'          => 'nullable|int',
            'address'                => 'nullable|string',
            'credit_code'            => 'nullable|string|max:18',
            'business_scope'         => 'nullable|string|max:2000',
            'start_time'             => 'nullable|int',
            'staff_num'              => 'nullable|int',
            'return_num'             => 'nullable|int',
            'not_return_num'         => 'nullable|int',
            'six_category'           => 'nullable|int',
            'isolation_room'         => 'nullable|int',
            'is_disinfect'           => 'nullable|int',
            'measure_temperature'    => 'nullable|int',
            'is_propagate'           => 'nullable|int',
            'application_reason'     => 'nullable|string|max:500',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->apply_service->SaveApply($this->params, 2);

        return $this->Response($result);
    }

    /**
     * 获取储存数据
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function GetSave()
    {
        $result = $this->apply_service->GetSave();

        return $this->Response($result);
    }
}
