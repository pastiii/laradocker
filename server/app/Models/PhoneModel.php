<?php

namespace App\Models;


class PhoneModel extends BaseModel
{
    protected $table = FIX . 'phone';

    public $timestamps = false;

    protected $fillable = ['phone'];

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
                case 'phone':
                    $res->whereRaw(
                        'a.phone = ' . $val
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
    public function GetUser($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return $result;
        }

        return false;
    }
}
