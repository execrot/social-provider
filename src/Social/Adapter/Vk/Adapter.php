<?php

namespace Social\Adapter\Vk;

use Social\Adapter\AbstractAdapter;
use Social\Model;

/**
 * Class Adapter
 * @package Social\Adapter\Vk
 */
class Adapter extends AbstractAdapter
{
    /**
     * @var Sdk\VK
     */
    private $_vk = null;

    /**
     * Initialization VKontakte SDK
     */
    public function init()
    {
        parent::init();

        $this->_vk = new Sdk\VK(
            $this->getSettings()->getId(),
            $this->getSettings()->getSecret()
        );
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->_vk->getAuthorizeUrl(
            $this->getSettings()->getScope(),
            $this->getSettings()->getRedirect()
        );
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        try {
            return $this->_vk->getAccessToken(
                $this->getCode(),
                $this->getSettings()->getRedirect()
            );
        }
        catch(\Exception $e) {}

        return false;
    }

    /**
     * @return Model\User
     */
    public function getUserData()
    {
        if (!($accessTokenData = $this->getAccessToken())) {
            return false;
        }

        $data = array(
            'accessToken' => $accessTokenData['access_token'],
            'email' => $accessTokenData['email'],
            'id' => $accessTokenData['user_id'],
        );

        $user = $this->_vk->api('users.get', array(
            'fields' => 'sex'
        ))['response'][0];

        $userData = array_merge($data, $user);

        $userModel = new Model\User();

        $userModel->setId($userData['uid']);
        $userModel->setEmail($userData['email']);
        $userModel->setName(implode(' ', array($userData['first_name'], $userData['last_name'])));

        switch ($userData['sex']) {
            case '2':
                $userModel->setGender('male');
                break;

            case '1':
                $userModel->setGender('female');
                break;
        }

        return $userModel;
    }
}
