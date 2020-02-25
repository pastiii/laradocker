<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends BaseController
{
    /** @var  UserService */
    protected $user_service;

    /** @var array 字段别名 */
    protected $custom = [
        'uname'           => '登录账号',
        'pwd'             => '账号密码',
        'pwd_two'         => '重复密码',
        'user_name'       => '归属人姓名',
        'user_phone'      => '归属人手机号',
        'user_type'       => '账号类型',
        'is_look'         => '移动端权限',
        'management_area' => '负责区域',
    ];

    /**
     * 初始化数据
     * UserController constructor.
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->user_service = new UserService();
    }

    /**
     * 创建用户
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function CreateUser(Request $request)
    {
        #校验规则
        $rule = [
            'uname'           => 'required|string|max:32',
            'pwd'             => 'required|min:6|max:24|string',
            'pwd_two'         => 'required|same:pwd|string',
            'user_name'       => 'required|max:10',
            'user_phone'      => 'required|regex:/^1\d{10}$/|string',
            'user_type'       => 'required|int',
            'is_look'         => 'required|int',
            'management_area' => 'required|string',
            'apply'           => 'nullable|string',
        ];

        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #储存数据
        $result = $this->user_service->CreateUser($this->params);

        return $this->Response($result);
    }

    /**
     * 编辑账号
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function EditUser(Request $request)
    {
        #校验规则
        $rule = [
            'id'              => 'required|int',
            'pwd'             => 'nullable|min:6|max:24|string',
            'pwd_two'         => 'nullable|same:pwd|string',
            'user_name'       => 'nullable|max:10',
            'user_phone'      => 'nullable|regex:/^1\d{10}$/|string',
            'user_type'       => 'nullable|int',
            'is_look'         => 'nullable|int',
            'management_area' => 'required|string',
            'apply'           => 'nullable|string',
        ];


        #字段校验
        $this->validate($request, $rule, [], $this->custom);

        #编辑数据
        $result = $this->user_service->EditUser($this->params);

        return $this->Response($result);
    }

    /**
     * 获取账号信息
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function UserDetail()
    {
        $result = $this->user_service->UserDetail($this->params);

        return $this->Response($result);
    }

    /**
     * 获取账号list
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     */
    public function UserList()
    {
        $result = $this->user_service->UserList($this->params, $this->is_page, $this->per_page);

        return $this->Response($result);
    }

    /**
     * 冻结/解冻
     * @return \Illuminate\Http\JsonResponse
     */
    public function LockUser()
    {
        $result = $this->user_service->LockUser($this->params);

        return $this->Response($result);
    }

    /**
     * 删除用户
     * @return \Illuminate\Http\JsonResponse
     */
    public function DelUser()
    {
        $result = $this->user_service->DelUser($this->params);

        return $this->Response($result);
    }
}
