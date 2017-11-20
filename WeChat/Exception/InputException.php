<?php
/**
 * Created by PhpStorm.
 * User: Malson
 * Date: 2017/11/16
 * Time: 下午1:54
 */

namespace WeChat\Exception;


class InputException extends \Exception
{
    public function __toString ()
    {
        return $this->message;
    }
}