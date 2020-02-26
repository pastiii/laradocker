<?php

namespace App\Http\Middleware;

use App\Exceptions\ExampleException;
use App\Models\UserModel;
use Closure;
use Illuminate\Support\Facades\Cache;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        $token = $request->header('authorization');
//        if (empty($token) || empty(Cache::get($token))) {
//            throw new ExampleException('登陆过期,请重新登陆', LOGIN_ERROR);
//        }
//
//        #获取用户信息
//        $user_info = Cache::get($token);
//
//        #获取用户状态
//        $where = [
//            'id'      => $user_info['id'],
//            'is_lock' => 1,
//            'is_del'  => 1
//        ];
//
//        $res = (new UserModel())->GetUser($where);
//
//        if (!$res) {
//            throw new ExampleException('账号已被冻结或删除', USER_LOCK);
//        }

        return $next($request);
    }
}
