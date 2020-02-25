<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\ApprovalService;
use App\Services\ExportService;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class ApprovalController extends BaseController
{
    /** @var  ApprovalService */
    protected $approval_service;

    /** @var array 字段别名 */
    protected $custom = [
        'enterprise_name' => '企业名称',
        'code'            => '审批编号',
        'state'           => '审批状态',
        'opinion'         => '审批意见',
        'remark'          => '审批备注',
    ];

    /**
     * 初始化数据
     * ApprovalService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->approval_service = new ApprovalService();
    }


    /**
     * 审批列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ApprovalList(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name' => 'nullable|string',
            'code'            => 'nullable|string',
            'state'           => 'nullable|int',
            'type'            => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->approval_service->ApprovalList($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 审批详情
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ApprovalDetail(Request $request)
    {
        #校验规则
        $rule = [
            'id' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->approval_service->ApprovalDetail($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 审批
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function Approval(Request $request)
    {
        #校验规则
        $rule = [
            'id'      => 'required|int',
            'type'    => 'required|int',
            'opinion' => 'required|string|max:500',
            'remark'  => 'nullable|string|max:500',
            'state'   => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->approval_service->Approval($this->params);

        return $this->Response($result);
    }

    /**
     * 企业人员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function Enterprise(Request $request)
    {
        #校验规则
        $rule = [
            'id' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = (new StatisticsService())->Enterprise1($this->params);

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
        #校验规则
        $rule = [
            'id' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->approval_service->ApprovalProcess($this->params);

        return $this->Response($result);
    }

    public function ApplyList(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name' => 'nullable|string',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->approval_service->ApplyList($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    public function Export()
    {
        (new ExportService())->Export();
    }

    /**
     * pdf
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Mpdf\MpdfException
     */
    public function PdfFile(Request $request)
    {
        #校验规则
        $rule = [
            'id' => 'required|int',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        $result = $this->approval_service->PdfFile($this->params);

        return $this->Response($result);
    }
}
