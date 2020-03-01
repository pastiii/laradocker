<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Services\StaffService;
use Illuminate\Http\Request;

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
     * 创建员工
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function CreateStaff(Request $request)
    {
        #校验规则
        $rule = [
            'staff_name'     => 'required|string|max:10',
            'staff_phone'    => 'required|string|regex:/^1\d{10}$/',
            'referrer_name'  => 'nullable|max:10',
            'referrer_phone' => 'nullable|string|regex:/^1\d{10}$/',
            'company'        => 'required',
        ];


        $msg = [
            'staff_name'     => '本人姓名',
            'staff_phone'    => '本人电话',
            'referrer_name'  => '推荐人姓名',
            'referrer_phone' => '推荐人电话',
            'company'        => '企业名称',
        ];

        #字段校验
        $this->validate($request, $rule, [], $msg);

        $result = $this->staff_service->CreateStaff($this->params);

        return $this->Response($result);
    }
}
