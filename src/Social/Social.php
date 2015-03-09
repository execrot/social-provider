<?php

namespace Social;
use Social\Adapter;

/**
 * Class Social
 * @package Social
 */
class Social implements InterfaceSocial
{
    /**
     * @var Adapter\InterfaceAdapter
     */
    protected $_adapter = null;

    /**
     * @param Adapter\InterfaceAdapter $adapter
     */
    public function setAdapter(Adapter\InterfaceAdapter $adapter)
    {
        $this->_adapter = $adapter;
    }

    /**
     * @return Adapter\InterfaceAdapter
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->getAdapter()->getLoginUrl();
    }

    /**
     * @return Model\User
     */
    public function getUserData()
    {
        return $this->getAdapter()->getUserData();
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->getAdapter()->setCode($code);
    }
}