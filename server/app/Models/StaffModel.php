<?php

namespace App\Models;


class StaffModel extends BaseModel
{
    protected $table = FIX . 'enterprise_staff';

    public $timestamps = false;

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

}
