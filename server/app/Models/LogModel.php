<?php

namespace App\Models;


class LogModel extends BaseModel
{
    protected $table = FIX . 'log';

    public $timestamps = false;

    public $fillable = ['address_id', 'enterprise_id', 'enterprise_name', 'file_name', 'file_url', 'update_num', 'add_num', 'import_time', 'ctime', 'c_user_id'];

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
                case 'state':
                    $res->whereRaw(
                        'a.state = ' . $val
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
