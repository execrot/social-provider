<?php

namespace Social\Exception;

/**
 * Class SocialAdapterNotFound
 * @package Social
 */
class SocialAdapterNotFound extends \Exception
{
    /**
     * @param string $socialId
     */
    public function __construct($socialId)
    {
        parent::__construct("Social adapter named {$socialId} was not found");
    }
}