<?php

namespace Social\Adapter\Facebook\Exception;

/**
 * Class CodeWasNotProvided
 * @package Social\Adapter\Facebook\Exception
 */
class CodeWasNotProvided extends \Exception
{
    public function __construct()
    {
        parent::__construct("Code was not provided");
    }
}