<?php

namespace Social\Adapter;

use Social\Settings;

/**
 * Class AbstractAdapter
 * @package Social\Adapter
 */
abstract class AbstractAdapter implements InterfaceAdapter
{
    /**
     * @var Settings\InterfaceSettings
     */
    private $_settings = null;

    /**
     * @param Settings\InterfaceSettings $settings
     * @return void
     */
    public function setSettings(Settings\InterfaceSettings $settings)
    {
        $this->_settings = $settings;
    }

    /**
     * @return Settings\InterfaceSettings
     */
    public function getSettings()
    {
        return $this->_settings;
    }

    /**
     * @var string
     */
    private $_code = null;

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Calls when settings is added and adapter can make custom init
     */
    public function init() {}
}