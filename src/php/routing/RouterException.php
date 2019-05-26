<?php

class RouterException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        print_r($message);
    }
}