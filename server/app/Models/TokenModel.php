<?php

namespace App\Models;

class TokenModel extends BaseModel
{
    protected $table = FIX . 'token';

    public $timestamps = false;

    protected $fillable = ['token', 'user_id', 'type', 'ext_time', 'phone'];

    public function GetToken($where)
    {
        $result = $this->where($where)->first();

        if ($result) {
            return $result->toArray();
        }

        return false;
    }
}

