<?php

namespace App\Models;


class ProgrammeModel extends BaseModel
{
    protected $table = FIX . 'enterprise_expand';

    public $timestamps = false;

    protected $fillable = ['enterprise_id', 'programme'];

    public function sqlWhere($res, $where = [])
    {
        if (empty($where)) {
            return $this;
        }

        foreach ($where as $key => $val) {
            switch ($key) {
                case 'id':
                    $res->whereRaw(
                        'a.id = ' . $val
                    );
                    break;
                case 'enterprise_id':
                    $res->whereRaw(
                        'a.enterprise_id = ' . $val
                    );
                    break;
                case 'is_del':
                    $res->whereRaw(
                        'a.is_del = ' . $val
                    );
                    break;
                case 'uname':
                    $res->where(
                        "a.uname", "like", "%$val%"
                    );
                    break;
                case 'user_name':
                    $res->where(
                        "a.user_name", "like", "%$val%"
                    );
                    break;
                case 'user_phone':
                    $res->where(
                        "a.user_phone", "like", "%$val%"
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
     * 检验是否填写
     * @param $where
     * @return bool
     */
    public function CheckUser($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return $result;
        }

        return false;
    }
}
