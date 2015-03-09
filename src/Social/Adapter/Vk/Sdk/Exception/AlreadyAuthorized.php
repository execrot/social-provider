<?php

/**
 * The exception class for VK library.
 * @author Vlad Pronsky <vladkens@yandex.ru>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 * @version 0.1.6
 */

namespace Social\Adapter\Vk\Sdk\Exception;

class AlreadyAuthorized extends \Exception
{
    public function __construct()
    {
        parent::__construct("Already authorized");
    }
}