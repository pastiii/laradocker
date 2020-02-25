<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2020/1/2
 * Time: 上午11:33
 */


/**
 * 生成密码
 * @param $pwd
 * @return string
 */
function PwdMd5($pwd) {
    return md5(md5($pwd));
}

function CreateToken($id) {
    $str = time().$id;

    return md5($str);
}