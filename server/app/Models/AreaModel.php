<?php

namespace App\Models;

class AreaModel extends BaseModel
{
    protected $table = FIX . 'area';

    public $timestamps = false;

    public function sqlWhere($res, $where = [])
    {
        if (empty($where)) {
            return $this;
        }

        foreach ($where as $key => $val) {
            switch ($key) {
                case 's.is_del':
                    $res->whereRaw(
                        's.is_del = ' . $val
                    );
                    break;
                case 'parent_id':
                    $res->whereRaw(
                        'a.parent_id = ' . $val
                    );
                    break;
                case 'township':
                    $res->where(
                        "a.township", "like", "%$val%"
                    );
                    break;
                case 'id':
                    $res->whereIn(
                        'a.id', $val
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
