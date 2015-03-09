<?php

namespace Social;

use Social\Adapter;
use Social\Model;

/**
 * Interface InterfaceSocial
 * @package Social
 */
interface InterfaceSocial
{
    /**
     * @param Adapter\InterfaceAdapter $adapter
     * @return void
     */
    public function setAdapter(Adapter\InterfaceAdapter $adapter);

    /**
     * @return Adapter\InterfaceAdapter
     */
    public function getAdapter();

    /**
     * @return string
     */
    public function getLoginUrl();

    /**
     * @return Model\User
     */
    public function getUserData();
}