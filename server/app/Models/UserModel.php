<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = FIX . 'user';

    public $timestamps = false;

    protected $fillable = ['uname', 'pwd', 'user_name', 'user_phone', 'user_type', 'is_look', 'is_lock', 'management_area', 'apply', 'ctime', 'c_user_id', 'utime', 'u_user_id'];

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
                case 'is_del':
                    $res->whereRaw(
                        'a.is_del = ' . $val
                    );
                    break;
                case 'user_type':
                    $res->whereRaw(
                        'a.user_type = ' . $val
                    );
                    break;
                case 'uname':
                    $res->where(
                        "a.uname", "like", "%$val%"
                    );
                    break;
                case 'uname1':
                    $res->where(
                        'a.uname', $val
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
     * 检验账号
     * @param $where
     * @return bool
     */
    public function CheckUser($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return true;
        }

        return false;
    }
    /**
     * 检验账号
     * @param $where
     * @return bool
     */
    public function GetUser($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return $result->toArray();
        }

        return false;
    }
}
