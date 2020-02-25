<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\ImportService;
use App\Services\StaffService;
use Illuminate\Http\Request;
use Excel;

class StaffController extends BaseController
{
    /** @var  StaffService */
    protected $staff_service;

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->staff_service = new StaffService();
    }

    /**
     * 企业员工列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function StaffList(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_id' => 'required|int',
        ];

        $msg = [
            'enterprise_id.required' => '请选择正确的企业!',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);

        $result = $this->staff_service->StaffList($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 人员信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function StaffDetail(Request $request)
    {
        #校验规则
        $rule = [
            'id' => 'required|int',
        ];

        $msg = [
            'id.required' => '请选择正确的员工!',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);

        $result = $this->staff_service->StaffDetail($this->params);

        return $this->Response($result);
    }

    /**
     * 企业列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function EnterpriseList()
    {
        $result = $this->staff_service->EnterpriseList($this->params);

        return $this->Response($result);
    }

    /**
     * 编辑防疫方案
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function EditProgramme(Request $request)
    {
        #校验规则
        $rule = [
            'programme'     => 'required',
            'enterprise_id' => 'required|int',
        ];

        $msg = [
            'programme.required'     => '请填写复产防疫方案!',
            'enterprise_id.required' => '请选择正确的企业!',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);

        $result = $this->staff_service->EditProgramme($this->params);

        return $this->Response($result);
    }

    public function Import(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name' => 'required',
            'enterprise_id'   => 'required',
            'file_name'       => 'required',
            'file_url'        => 'required',
            'address_id'      => 'required',
            'import_time'     => 'required',
        ];

        $msg = [
            'enterprise_name.required' => '企业名称不可为空!',
            'enterprise_id.required'   => '请选择企业!',
            'file_name.required'       => '文件名称不可为空!',
            'file_url.required'        => '文件路径不可为空!',
            'address_id.required'      => '请选择地址!',
            'import_time.required'     => '上报时间不可为空!',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);

        $service = new ImportService();
        $result = $service->Import($this->params);

        return $this->Response($result);
    }

    /**
     * 上报日志
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function ImportLog()
    {
        $result = $this->staff_service->ImportLog($this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 防疫方案内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function Programme(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_id' => 'required|int',
        ];

        $msg = [
            'enterprise_id.required' => '请选择正确的企业!',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);

        $result = $this->staff_service->Programme($this->params);

        return $this->Response($result);
    }

}
