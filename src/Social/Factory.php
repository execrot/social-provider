<?php

namespace Social;
use Social\Settings;

/**
 * Class Factory
 * @package Social
 */
final class Factory
{
    /**
     * @var array
     */
    protected static $_config = null;

    /**
     * @param string $option
     *
     * @return array
     */
    public static function getConfig($option = null)
    {
        if (!empty(self::$_config[$option])) {
            return self::$_config[$option];
        }
        return self::$_config;
    }

    /**
     * @param array $config
     */
    public static function setConfig(array $config = array())
    {
        self::$_config = $config;
    }

    /**
     * @param string $socialId
     * @return Social
     *
     * @throws Exception\SocialAdapterNotFound
     * @throws Exception\SocialSettingsWasNotProvided
     */
    public static function factory($socialId)
    {
        $socialSettings = self::getConfig($socialId);

        if (empty($socialSettings)) {
            throw new Exception\SocialSettingsWasNotProvided($socialId);
        }

        $socialAdapter = self::_getSocialAdapter($socialId);

        $settings = new Settings\Settings();
        $settings->setSettings($socialSettings);
        $socialAdapter->setSettings($settings);
        $socialAdapter->init();

        $social = new Social();
        $social->setAdapter($socialAdapter);

        return $social;
    }

    /**
     * @param string $socialId
     *
     * @return Adapter\InterfaceAdapter
     * @throws Exception\SocialAdapterNotFound
     */
    private static function _getSocialAdapter($socialId)
    {
        $socialAdapter = null;

        switch ($socialId) {

            case 'facebook':
                return new Adapter\Facebook\Adapter();

            case 'vk':
                return new Adapter\Vk\Adapter();
        }

        if (!$socialAdapter) {
            throw new Exception\SocialAdapterNotFound($socialId);
        }
    }
}