<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\BaseController;
use App\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LoginController extends BaseController
{
    /** @var  LoginService */
    protected $login_service;


    /**
     * 初始化数据
     * EnterpriseController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->login_service = new LoginService();
    }

    /**
     * 账号登陆
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function Login(Request $request)
    {
        #校验规则
        $rule = [
            'uname' => 'required|max:32',
            'pwd'   => 'required|max:24',
        ];

        $msg = [
            'uname.required' => '请输入正确的账号、密码',
            'pwd.required'   => '请输入正确的账号、密码',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);
        $result = $this->login_service->Login($this->params, 2);
        return $this->Response($result);
    }

    /**
     * 手机号码登陆
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ExampleException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function PhoneLogin(Request $request)
    {
        #校验规则
        $rule = [
            'phone' => 'required|regex:/^1\d{10}$/|string',
            'code'  => 'required|max:4',
        ];

        $msg = [
            'phone.required' => '请输入正确的手机号码',
            'code.required'  => '请输入正确的验证码',
        ];

        #字段校验
        $this->validate($request, $rule, $msg);
        $result = $this->login_service->PhoneLogin($this->params, 4);
        return $this->Response($result);
    }

    /**
     * 登出
     * @return \Illuminate\Http\JsonResponse
     */
    public function Logout(Request $request)
    {
        $res = Cache::forget($request->header('authorization'));

        if ($res) {
            $result = [
                'status' => RETURN_SUCCESS,
                'data'   => NULL,
                'msg'    => CHINESE_MSG[RETURN_SUCCESS],
            ];
        }else{
            $result = [
                'status' => RETURN_FILED,
                'data'   => NULL,
                'msg'    => CHINESE_MSG[RETURN_FILED],
            ];
        }

        return $this->Response($result);
    }
}
