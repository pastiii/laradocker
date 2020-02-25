<?php

namespace App\Models;


class ApplySaveModel extends BaseModel
{
    protected $table = FIX . 'apply_save';

    public $timestamps = false;

    protected $fillable = [
        'enterprise_name', 'enterprise_id', 'juridical_person', 'contacts', 'juridical_person_phone', 'credit_code',
        'address_id', 'address', 'business_scope', 'start_time', 'staff_num', 'return_num', 'not_return_num',
        'six_category', 'isolation_room', 'is_disinfect', 'measure_temperature', 'is_propagate', 'application_reason',
        'file_one', 'file_two', 'file_three', 'file_one_name', 'file_two_name', 'file_three_name', 'ctime', 'c_user_id', 'utime', 'u_user_id'
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
                case 'c_user_id':
                    $res->whereRaw(
                        'a.c_user_id = ' . $val
                    );
                    break;
                case 'enterprise_id':
                    $res->whereRaw(
                        'a.enterprise_id = ' . $val
                    );
                    break;
                case 'township':
                    $res->where(
                        "a.township", "like", "%$val%"
                    );
                    break;
                case 'enterprise_name':
                    $res->where(
                        "a.enterprise_name", "like", "%$val%"
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
}
