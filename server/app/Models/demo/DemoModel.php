<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2019/12/31
 * Time: 下午2:58
 */

namespace App\Models\demo;

use App\Models\BaseModel;

class DemoModel extends BaseModel
{
    protected $table = FIX.'user';

//    protected $fillable = ['name', 'email', 'password'];

    public function sqlWhere($res, $where = [])
    {
        if (empty($where)) {
            return $this;
        }

        foreach ($where as $key => $val) {
            switch ($key) {
                case 'id':
                    $res->whereRaw(
                        'a.id = '. $val
                    );
                    break;
                case 'id_in':
                    $res->whereIn(
                        'a.id', $val
                    );
                    break;
                case 'email':
                    $res->where(
                        "a.email", $val
                    );
                    break;
                case 'join':
                    $res->whereRaw(
                        $val
                    );
                    break;
            }
        }
    }
}
