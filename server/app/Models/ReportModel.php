<?php

namespace App\Models;

class ReportModel extends BaseModel
{
    protected $table = FIX . 'mobile_report';

    public $timestamps = false;

    protected $fillable = ['contact_num', 'routine', 'suspected', 'diagnosis', 'death', 'management_area', 'ctime', 'c_user_id'];

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

    public function GetInfo($filed, $orderBy  = 'a.id desc')
    {
        $res = $this->from($this->table . ' as a')->selectRaw($filed)->orderByRaw($orderBy)->offset(0)->limit(2)->get()->toArray();
        return $res;
    }
}
