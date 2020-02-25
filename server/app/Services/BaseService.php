<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2020/1/15
 * Time: 下午2:02
 */

namespace App\Services;


use Illuminate\Support\Facades\Cache;

class BaseService
{
    protected $user_info;
    protected $user_id;

    public function __construct()
    {
        $token = \Request::header('authorization');

        if (!empty(Cache::get($token))) {
            $this->user_info = Cache::get($token);
            $this->user_id   = $this->user_info['id'];
        }
    }

}
