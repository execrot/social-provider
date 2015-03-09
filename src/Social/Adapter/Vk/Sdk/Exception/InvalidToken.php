<?php

/**
 * The exception class for VK library.
 * @author Vlad Pronsky <vladkens@yandex.ru>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 * @version 0.1.6
 */

namespace Social\Adapter\Vk\Sdk\Exception;

class InvalidToken extends \Exception
{
    /**
     * @param string $accessToken
     */
    public function __construct($accessToken)
    {
        parent::__construct("Invalid access token: {$accessToken}");
    }
}

