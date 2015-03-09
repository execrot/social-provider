<?php

namespace Social\Settings;

/**
 * Interface InterfaceSettings
 * @package Social\Settings
 */
interface InterfaceSettings
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @return string
     */
    public function getSecret();

    /**
     * @return string
     */
    public function getScope();

    /**
     * @return string
     */
    public function getRedirect();
}