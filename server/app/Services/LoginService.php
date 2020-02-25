<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/7
 * Time: 22:36
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\PhoneModel;
use App\Models\TokenModel;
use App\Models\UserModel;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LoginService extends BaseService
{
    use ResponseTrait;

    /** @var UserModel */
    protected $user_model;

    /** @var TokenModel */
    protected $token_model;


    public function __construct()
    {
        parent::__construct();
        $this->user_model  = new UserModel();
        $this->token_model = new TokenModel();
    }

    /**
     * 登陆
     * @param $params
     * @param int $type
     * @return array
     * @throws ExampleException
     */
    public function Login($params, $type = 1)
    {
        $where = [
            'uname1' => $params['uname'],
            'is_del' => 1,
        ];

        $filed = 'id, uname, pwd, user_name, user_phone, user_type, is_look, management_area, is_lock, apply';

        $user_info = $this->user_model->GetOne($filed, $where);

        if ($user_info['status'] != RETURN_SUCCESS) {
            throw new ExampleException(CHINESE_MSG[LOGIN_FAIL], LOGIN_FAIL);
        }

        if ($user_info['data']['is_lock'] != 1) {
            throw new ExampleException(CHINESE_MSG[USER_LOCK], USER_LOCK);
        }

        if ($user_info['data']['pwd'] != PwdMd5($params['pwd'])) {
            throw new ExampleException(CHINESE_MSG[LOGIN_FAIL], LOGIN_FAIL);
        }

        if ($type == 2) {
            if ($user_info['data']['is_look'] != 1) {
                throw new ExampleException(CHINESE_MSG[LOGIN_FAIL], LOGIN_FAIL);
            }
        }

        #生成登陆信息
        $token_where = [
            'type'    => $type,
            'user_id' => $user_info['data']['id']
        ];

        $res = $this->token_model->GetToken($token_where);

        if ($res) {
            #更新
            $data = [
                'token'    => CreateToken($user_info['data']['id']),
                'ext_time' => time() + 86400 * 30
            ];

            $this->token_model->EditData($token_where, $data);
        } else {
            #添加
            $data = [
                'token'    => CreateToken($user_info['data']['id']),
                'type'     => $type,
                'user_id'  => $user_info['data']['id'],
                'ext_time' => time() + 86400 * 30
            ];

            $this->token_model->AddData($data);
        }

        #数据处理
        unset($user_info['data']['pwd']);
        $user_info['data']['token'] = $data['token'];
        Cache::forever($data['token'], $user_info['data']);
        return $this->HandleData('other', $user_info['data']);
    }

    /**
     * 手机号码登陆
     * @param $params
     * @param int $type
     * @return array
     * @throws ExampleException
     */
    public function PhoneLogin($params, $type = 3)
    {
        #校验验证码
        $this->CheckCode($params);

        #生成登陆信息
        $token_where = [
            'type'  => $type,
            'phone' => $params['phone']
        ];

        $res = $this->token_model->GetToken($token_where);

        $ret = (new PhoneModel())->GetUser(['phone' => $params['phone']]);

        DB::beginTransaction();

        try {
            if (!$ret) {
                #添加操作
                $ret = (new PhoneModel())->AddData(['phone' => $params['phone']]);
            }

            if ($res) {
                #更新
                $data = [
                    'token'    => CreateToken($params['phone'] . $type),
                    'ext_time' => time() + 86400 * 30
                ];

                $this->token_model->EditData($token_where, $data);
            } else {
                #添加
                $data = [
                    'token'    => CreateToken($params['phone'] . $type),
                    'type'     => $type,
                    'user_id'  => 0,
                    'phone'    => $params['phone'],
                    'ext_time' => time() + 86400 * 30
                ];

                $this->token_model->AddData($data);
            }

            DB::commit();
        } catch (ExampleException $e) {

            DB::rollback();
            throw new ExampleException(CHINESE_MSG[RETURN_FILED], RETURN_FILED);

        }
        #数据处理
        $user_info['token'] = $data['token'];
        $user_info['phone'] = $params['phone'];
        $user_info['management_area'] = "All";
        $user_info['id']    = $ret->id;
        Cache::forever($data['token'], $user_info);
        return $this->HandleData('other', $user_info);
    }

    /**
     * 验证码校验
     * @param $params
     * @return bool
     * @throws ExampleException
     */
    public function CheckCode($params)
    {
        #获取缓存中的验证码
        $res = Cache::get($params['phone']);

        #万能验证码
        if ($params['code'] == 3250) {
            return true;
        }

        #如果不存在报失效或手机号码错误
        if (!$res) {
            throw new ExampleException(CHINESE_MSG[CHECK_CODE], CHECK_CODE);
        }

        #校验不正确报错误
        if ($params['code'] != $res) {
            throw new ExampleException(CHINESE_MSG[CHECK_CODE_FAIL], CHECK_CODE_FAIL);
        }

        #正确通过验证
        return true;
    }
}