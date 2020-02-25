<?php

namespace App\Models;

class ApprovalModel extends BaseModel
{
    protected $table = FIX . 'approval';

    public $timestamps = false;

    public function sqlWhere($res, $where = [])
    {
        if (empty($where)) {
            return $this;
        }

        foreach ($where as $key => $val) {
            switch ($key) {
                case 'type':
                    $res->whereRaw(
                        'a.type = ' . $val
                    );
                    break;
                case 'id':
                    $res->whereRaw(
                        'a.id = ' . $val
                    );
                    break;
                case 'apply_id':
                    $res->whereRaw(
                        'a.apply_id = ' . $val
                    );
                    break;
                case 'code_like':
                    $res->where(
                        "a.code", "like", "%$val%"
                    );
                    break;
                case 'enterprise_name':
                    $res->where(
                        "apply.enterprise_name", "like", "%$val%"
                    );
                    break;
                case 'state':
                    $res->whereIn(
                        'a.state', $val
                    );
                    break;
                case 'id_in':
                    $res->whereIn(
                        'a.id', $val
                    );
                    break;
            }
        }
    }

    /**
     * 检验账号
     * @param $where
     * @return bool
     */
    public function GetCode($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return true;
        }

        return false;
    }
}
