<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/7
 * Time: 3:22
 */

namespace App\Services;


use App\Exceptions\ExampleException;
use App\Models\ApplyModel;
use App\Models\EnterpriseModel;
use App\Models\UserModel;
use App\Traits\ResponseTrait;

class EnterpriseService extends BaseService
{
    use ResponseTrait;

    /** @var EnterpriseModel */
    protected $enterprise_model;

    /** @var UserModel */
    protected $user_model;

    public function __construct()
    {
        parent::__construct();
        $this->enterprise_model = new EnterpriseModel();
        $this->user_model       = new UserModel();
    }

    /**
     * 创建企业
     * @param $type
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function CreateEnterprise($params, $type = 0)
    {
        #校验企业是否存在
        $ret = $this->enterprise_model->CheckUser(['enterprise_name' => $params['enterprise_name'], 'is_del' => 1]);

        if ($ret) {
            throw new ExampleException('企业名称已存在', RETURN_FILED_FAIL);
        }

        $data = [
            'enterprise_name'        => $params['enterprise_name'],
            'juridical_person'       => $params['juridical_person'],
            'contacts'               => $params['contacts'],
            'juridical_person_phone' => $params['juridical_person_phone'],
            'address_id'             => $params['address_id'],
            'address'                => $params['address'],
            'ctime'                  => time(),
            'c_user_id'              => $this->user_id,
            'utime'                  => time(),
            'u_user_id'              => $this->user_id,
        ];

        if ($type == 5) {
            $data['c_user_id'] = 0;
            $data['u_user_id'] = 0;
        }


        $res = $this->enterprise_model->AddData($data);

        return $this->HandleData('add', $res);
    }


    /**
     * 编辑企业
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function EditEnterprise($params)
    {
        #校验企业是否存在
        $ret = $this->enterprise_model->CheckUser(['enterprise_name' => $params['enterprise_name'], 'is_del' => 1]);

        if ($ret && $ret->id != $params['id']) {
            throw new ExampleException('企业名称已存在', RETURN_FILED_FAIL);
        }
        #条件
        $where = [
            'id' => $params['id']
        ];

        #数据
        $data = array_filter([
            'enterprise_name'        => $params['enterprise_name'] ?? '',
            'juridical_person'       => $params['juridical_person'] ?? '',
            'contacts'               => $params['contacts'] ?? '',
            'juridical_person_phone' => $params['juridical_person_phone'] ?? '',
            'address_id'             => $params['address_id'] ?? '',
            'address'                => $params['address'] ?? '',
            'utime'                  => time(),
            'u_user_id'              => $this->user_id,
        ]);

        $res = $this->enterprise_model->EditData($where, $data);

        return $this->HandleData('edit', $res);
    }

    /**
     * 企业账户详情
     * @param $params
     * @return array
     * @throws ExampleException
     */
    public function EnterpriseDetail($params)
    {
        $filed = 'id, enterprise_name, juridical_person, contacts, juridical_person_phone, address_id, address';

        $where = [
            'id' => $params['id']
        ];

        $result = $this->enterprise_model->GetOne($filed, $where);

        return $result;
    }

    /**
     * 企业列表
     * @param $params
     * @param $is_page
     * @param $per_page
     * @return array
     * @throws ExampleException
     */
    public function EnterpriseList($params, $is_page, $per_page)
    {
        $filed = 'a.id, a.enterprise_name, a.juridical_person, a.contacts, a.juridical_person_phone, a.address_id, a.address, FROM_UNIXTIME(a.ctime, "%Y-%m-%d %H:%i:%s") as ctime, u.user_name';

        $where = array_filter([
            'enterprise_name'  => $params['enterprise_name'] ?? '',
            'address_id'       => $params['address_id'] ?? '',
            'juridical_person' => $params['juridical_person'] ?? '',
            'user_name'        => $params['user_name'] ?? '',
            'is_del'           => 1,
        ]);

        if (empty($params['address_id']) && $this->user_info['management_area'] != "All") {
            $where['address_id_in'] = explode(',', $this->user_info['management_area']);
        }

        $user_alias = MODEL_ALIAS[$this->user_model->TableName()];

        $join = [
            [
                'left',
                $this->user_model->TableName() . ' as ' . $user_alias,
                [
                    'and' => [$user_alias . '.id', '=', 'a.c_user_id'],
                ],
            ],
        ];

        $result = $this->enterprise_model->GetList($filed, $where, $join, '', 'a.id', $is_page, $per_page);

        return $result;
    }

    /**
     * 删除企业
     * @param $params
     * @return array
     */
    public function DelEnterprise($params)
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

        $res = $this->enterprise_model->EditData($where, $data);

        return $this->HandleData('edit', $res);
    }

    /**
     * 上报企业list
     * @return array
     * @throws ExampleException
     */
    public function ApplyEnterprise()
    {
        $company_info = (new ApplyModel())->GetList('enterprise_id', ['state' => [1, 2, 3, 4]]);

        $ids = [];
        if ($company_info['status'] == RETURN_SUCCESS) {
            foreach ($company_info['data'] as $val) {
                array_push($ids, $val);
            }
        }

        $filed = 'id, enterprise_name, address_id, juridical_person, juridical_person_phone, address, contacts';

        $where = array_filter([
            'is_del'    => 1,
            'id_not_in' => $ids ?? ''
        ]);

        $result = $this->enterprise_model->GetList($filed, $where);

        return $result;
    }
}