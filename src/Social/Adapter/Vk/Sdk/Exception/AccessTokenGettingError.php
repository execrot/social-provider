<?php

/**
 * The exception class for VK library.
 * @author Vlad Pronsky <vladkens@yandex.ru>
 * @license https://raw.github.com/vladkens/VK/master/LICENSE MIT
 * @version 0.1.6
 */

namespace Social\Adapter\Vk\Sdk\Exception;

class AccessTokenGettingError extends \Exception
{
    /**
     * @param string $error
     * @param string $description
     */
    public function __construct($error, $description)
    {
        parent::__construct("Error with access token geting. Error: {$error}. Description: {$description}");
    }
}

