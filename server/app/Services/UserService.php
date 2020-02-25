<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/6
 * Time: 17:34
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\UserModel;
use App\Traits\ResponseTrait;

class UserService extends BaseService
{
    use ResponseTrait;

    /** @var UserModel */
    protected $user_model;

    public function __construct()
    {
        parent::__construct();
        $this->user_model = new UserModel();
    }

    /**
     * 添加用户
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function CreateUser($params)
    {
        #校验账号是否存在
        $ret = $this->user_model->CheckUser(['uname' => $params['uname'], 'is_del' => 1]);

        if ($ret) {
            throw new ExampleException('账号已存在', RETURN_FILED_FAIL);
        }

        #数据处理
        $data = [
            'uname'           => $params['uname'],
            'pwd'             => PwdMd5($params['pwd']),
            'user_name'       => $params['user_name'],
            'user_phone'      => $params['user_phone'],
            'user_type'       => $params['user_type'],
            'is_look'         => $params['is_look'],
            'management_area' => $params['management_area'] ?? 'All',
            'apply'           => $params['apply'] ?? NULL,
            'ctime'           => time(),
            'c_user_id'       => $this->user_id,
            'utime'           => time(),
            'u_user_id'       => $this->user_id,
        ];

        $res = $this->user_model->AddData($data);

        return $this->HandleData('add', $res);
    }

    /**
     * 编辑账号
     * @param $params
     * @return array
     */
    public function EditUser($params)
    {
        #条件
        $where = [
            'id' => $params['id']
        ];

        #数据处理
        $data = array_filter([
            'pwd'             => empty($params['pwd']) ? '' : PwdMd5($params['pwd']),
            'user_name'       => $params['user_name'] ?? '',
            'user_phone'      => $params['user_phone'] ?? '',
            'user_type'       => $params['user_type'] ?? '',
            'is_look'         => $params['is_look'] ?? '',
            'management_area' => $params['management_area'] ?? 'All',
            'utime'           => time(),
            'u_user_id'       => $this->user_id,
        ]);

        $data['apply'] = $params['apply'] ?? NULL;

        $res = $this->user_model->EditData($where, $data);

        return $this->HandleData('edit', $res);
    }

    /**
     * 获取账户详情
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function UserDetail($params)
    {
        $filed = 'id, uname, user_name, user_phone, user_type, is_look, management_area, apply';

        $where = [
            'id' => $params['id']
        ];

        $result = $this->user_model->GetOne($filed, $where);

        return $result;
    }

    /**
     * 账户列表
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws ExampleException
     */
    public function UserList($params, $is_page, $per_page)
    {
        $filed = 'id, uname, user_name, user_phone, user_type, is_lock, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime';

        $where = array_filter([
            'uname'      => $params['uname'] ?? '',
            'user_name'  => $params['user_name'] ?? '',
            'user_phone' => $params['user_phone'] ?? '',
            'user_type'  => $params['user_type'] ?? '',
            'is_del'     => 1
        ]);

        $result = $this->user_model->GetList($filed, $where, [], '', '', $is_page, $per_page);

        return $result;
    }

    /**
     * 冻结用户
     * @param $params
     * @return array
     */
    public function LockUser($params)
    {
        #条件
        $where = [
            'id' => $params['id']
        ];

        #修改
        $data = [
            'is_lock'   => $params['is_lock'],
            'utime'     => time(),
            'u_user_id' => $this->user_id,
        ];

        $res = $this->user_model->EditData($where, $data);

        return $this->HandleData('edit', $res);
    }

    /**
     * 删除用户
     * @param $params
     * @return array
     */
    public function DelUser($params)
    {
        #条件
        $where = [
            'id' => $params['id']
        ];

        #修改
        $data = [
            'is_del'    => 2,
            'utime'     => time(),
            'u_user_id' => $this->user_id,
            'dtime'     => time(),
            'd_user_id' => $this->user_id,
        ];

        $res = $this->user_model->EditData($where, $data);

        return $this->HandleData('edit', $res);
    }
}