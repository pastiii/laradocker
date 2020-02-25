<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2019/12/31
 * Time: 上午10:33
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected $is_page;  #是否分页
    protected $per_page; #分页条数
    protected $params;   #传参

    public function __construct(Request $request)
    {
        $this->is_page  = $request->page ?? NULL;
        $this->per_page = $request->per_page ?? 10;
        $this->params   = $request->input() ?? NULL;
    }

    /**
     * 数据返回
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function Response($data)
    {
        $data = [
            'status' => $data['status'],
            'data'   => $data['data'],
            'msg'    => $data['msg'],
        ];

        return response()->json($data);
    }
}
