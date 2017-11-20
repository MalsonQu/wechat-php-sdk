<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/17
 * Time: 上午9:02
 */

namespace WeChat\Exception;


class ParamException extends \Exception
{
    public function __toString ()
    {
        return $this->message;
    }
}