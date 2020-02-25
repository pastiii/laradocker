<?php
/**
 * Created by PhpStorm.
 * User: ZXQ
 * Date: 2020/1/15
 * Time: 上午10:03
 */

namespace App\Exceptions;

use Exception;

class ExampleException extends Exception
{
    function __construct($error_msg = '', $error_status = RETURN_ERROR)
    {
        $this->error_msg    = $error_msg;
        $this->error_status = $error_status;
        parent::__construct($error_msg, $error_status);
    }
}
