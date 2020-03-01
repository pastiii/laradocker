<?php

namespace App\Models;


class StaffModel extends BaseModel
{
    protected $table = FIX . 'staff';

    public $timestamps = false;

    protected $fillable = [
        'staff_name', 'id_card', 'staff_phone', 'referrer_name', 'referrer_phone', 'company', 'state', 'ctime', 'c_user_id', 'utime', 'u_user_id'
    ];

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
                case 'con':
                    $res->Orwhere(
                        "a.staff_name", "like", "%$val%"
                    );
                    $res->Orwhere(
                        "a.staff_phone", "like", "%$val%"
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
