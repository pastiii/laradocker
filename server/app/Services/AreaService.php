<?php
/**
 * Created by PhpStorm.
 * User: YYWQ
 * Date: 2020/2/7
 * Time: 13:06
 */

namespace App\Services;


use App\Models\AreaModel;

class AreaService extends BaseService
{
    /** @var AreaModel */
    protected $area_model;

    public function __construct()
    {
        parent::__construct();
        $this->area_model = new AreaModel();
    }

    /**
     * 地区下拉菜单
     * @return array
     * @throws \App\Exceptions\ExampleException
     */
    public function AreaSelect()
    {
        #todo 目前尚无其他地区参数暂时写死
        $filed = 'id, township, pac_name as address';

        $where = [
            'parent_id' => 3
        ];

        #查看账号权限
        if ($this->user_info['management_area'] != "All") {
            $where['id'] = explode(',', $this->user_info['management_area']);
        }

        $result = $this->area_model->GetList($filed, $where);

        return $result;
    }
}