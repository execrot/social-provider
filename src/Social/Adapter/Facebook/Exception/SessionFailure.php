<?php

namespace Social\Adapter\Facebook\Exception;

/**
 * Class SessionFailure
 * @package Social\Adapter\Facebook\Exception
 */
class SessionFailure extends \Exception
{
    public function __construct()
    {
        parent::__construct("Error with facebook session initialization");
    }
}