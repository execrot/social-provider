<?php

namespace Social\Adapter;

use Social\Model;
use Social\Settings;

/**
 * Interface InterfaceAdapter
 * @package Social\Adapter
 */
interface InterfaceAdapter
{
    /**
     * @return void
     */
    public function init();

    /**
     * @param Settings\InterfaceSettings $settings
     * @return void
     */
    public function setSettings(Settings\InterfaceSettings $settings);

    /**
     * @return Settings\InterfaceSettings
     */
    public function getSettings();

    /**
     * @return string
     */
    public function getLoginUrl();

    /**
     * @return string
     */
    public function getAccessToken();

    /**
     * @return Model\User
     */
    public function getUserData();

    /**
     * @param string $code
     * @return void
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getCode();
}