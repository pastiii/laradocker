<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2020/1/7
 * Time: 下午5:37
 */

namespace App\Traits;


trait ResponseTrait
{
    /**
     * 数据结构统一输出
     * @param $type
     * @param $data
     * @return array
     */
    public function HandleData($type, $data)
    {
        switch ($type) {
            case 'list':
                if ($data['status'] == RETURN_SUCCESS) {
                    $res = [
                        'status' => $data['status'],
                        'data'   => $data['data'],
                        'msg'    => CHINESE_MSG[$data['status']],
                    ];
                } else {
                    $res = [
                        'status' => $data['status'],
                        'data'   => NULL,
                        'msg'    => CHINESE_MSG[$data['status']],
                    ];
                }
                break;
            case 'add':
                if ($data) {
                    $res = [
                        'status' => RETURN_SUCCESS,
                        'data'   => ['id' => $data->id],
                        'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                    ];
                } else {
                    $res = [
                        'status' => RETURN_DATA_EMPTY,
                        'data'   => NULL,
                        'msg'    => CHINESE_MSG[RETURN_DATA_EMPTY],
                    ];
                }
                break;
            case 'edit':
                if ($data) {
                    $res = [
                        'status' => RETURN_SUCCESS,
                        'data'   => NULL,
                        'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                    ];
                } else {
                    $res = [
                        'status' => RETURN_FILED,
                        'data'   => NULL,
                        'msg'    => CHINESE_MSG[RETURN_FILED],
                    ];
                }
                break;
            case 'other':
                if ($data) {
                    $res = [
                        'status' => RETURN_SUCCESS,
                        'data'   => $data,
                        'msg'    => CHINESE_MSG[RETURN_SUCCESS],
                    ];
                } else {
                    $res = [
                        'status' => $data['status'],
                        'data'   => NULL,
                        'msg'    => CHINESE_MSG[$data['status']],
                    ];
                }
                break;
            default:
                $res = [
                    'status' => RETURN_ERROR,
                    'data'   => NULL,
                    'msg'    => CHINESE_MSG[RETURN_ERROR],
                ];
        }

        return $res;
    }
}
