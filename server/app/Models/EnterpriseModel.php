<?php

namespace App\Models;

class EnterpriseModel extends BaseModel
{
    protected $table = FIX . 'enterprise';

    public $timestamps = false;

    protected $fillable = ['enterprise_name', 'juridical_person', 'contacts', 'juridical_person_phone', 'address_id', 'address', 'ctime', 'c_user_id', 'utime', 'u_user_id'];

    public function sqlWhere($res, $where = [])
    {
        if (empty($where)) {
            return $this;
        }

        foreach ($where as $key => $val) {
            switch ($key) {
                case 'id_not_in':
                    $res->whereNotIn(
                        'a.id', $val
                    );
                    break;
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
                case 'address_id':
                    $res->whereRaw(
                        'a.address_id = ' . $val
                    );
                    break;
                case 'user_name':
                    $res->where(
                        "u.user_name", "like", "%$val%"
                    );
                    break;
                case 'juridical_person':
                    $res->where(
                        "a.juridical_person", "like", "%$val%"
                    );
                    break;
                case 'enterprise_name':
                    $res->where(
                        "a.enterprise_name", "like", "%$val%"
                    );
                    break;
                case 'address_id_in':
                    $res->whereIn(
                        'a.address_id', $val
                    );
                    break;
            }
        }
    }

    /**
     * 检验企业
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
