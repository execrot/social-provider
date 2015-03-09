<?php

namespace Social\Exception;

/**
 * Class SocialSettingsWasNotProvided
 * @package Social
 */
class SocialSettingsWasNotProvided extends \Exception
{
    /**
     * @param string $socialId
     */
    public function __construct($socialId)
    {
        parent::__construct("Social adapter named {$socialId} was not found");
    }
}