<?php

namespace Social\Settings;

/**
 * Class Settings
 * @package Social\Settings
 */
class Settings implements InterfaceSettings
{
    /**
     * @var array
     */
    protected static $_settings = array();

    /**
     * @param array $settings
     * @return void
     */
    public static function setSettings(array $settings)
    {
        self::$_settings = $settings;
    }

    /**
     * @return null|string
     */
    public function getId()
    {
        return $this->_getSetting('id');
    }

    /**
     * @return null|string
     */
    public function getSecret()
    {
        return $this->_getSetting('secret');
    }

    /**
     * @return null|string
     */
    public function getScope()
    {
        return $this->_getSetting('scope');
    }

    /**
     * @return null|string
     */
    public function getRedirect()
    {
        return $this->_getSetting('redirect');
    }

    /**
     * @param string $settingName
     * @return null|string
     */
    private function _getSetting($settingName)
    {
        if (isset(self::$_settings[$settingName])) {
            return self::$_settings[$settingName];
        }

        return null;
    }
}