<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\EnterpriseService;
use Illuminate\Http\Request;

class EnterpriseController extends BaseController
{
    /** @var  EnterpriseService */
    protected $enterprise_service;

    /** @var array 字段别名 */
    protected $custom = [
        'enterprise_name'        => '企业名称',
        'juridical_person'       => '法人名称',
        'juridical_person_phone' => '联系方式',
        'address_id'             => '所在地区',
        'address'                => '详细地址',
    ];

    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->enterprise_service = new EnterpriseService();
    }

    /**
     * 创建企业
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function CreateEnterprise(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name'        => 'required|string|max:32',
            'juridical_person'       => 'required|max:10|string',
            'contacts'               => 'required|max:10|string',
            'juridical_person_phone' => 'required|regex:/^1\d{10}$/|string',
            'address_id'             => 'required|int',
            'address'                => 'required|string',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->enterprise_service->CreateEnterprise($this->params);

        return $this->Response($result);
    }

    /**
     * 编辑企业
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function EditEnterprise(Request $request)
    {
        #校验规则
        $rule = [
            'enterprise_name'        => 'nullable|string|max:32',
            'juridical_person'       => 'nullable|max:10|string',
            'contacts'               => 'nullable|max:10|string',
            'juridical_person_phone' => 'nullable|regex:/^1\d{10}$/|string',
            'address_id'             => 'nullable|int',
            'address'                => 'nullable|string',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->enterprise_service->EditEnterprise($this->params);

        return $this->Response($result);
    }

    /**
     * 企业信息
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function EnterpriseDetail()
    {
        $result = $this->enterprise_service->EnterpriseDetail($this->params);

        return $this->Response($result);
    }

    /**
     * 获取企业list
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function EnterpriseList()
    {
        $result = $this->enterprise_service->EnterpriseList($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 删除企业
     * @return \Illuminate\Http\JsonResponse
     */
    public function DelEnterprise()
    {
        $result = $this->enterprise_service->DelEnterprise($this->params);

        return $this->Response($result);
    }
}
