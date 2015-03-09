<?php

namespace Social\Adapter\Facebook;

use Social\Adapter\AbstractAdapter;
use Social\Model;

/**
 * Class Adapter
 * @package Social\Adapter\Facebook
 */
class Adapter extends AbstractAdapter
{
    /**
     * @var \Facebook\FacebookRedirectLoginHelper
     */
    private $_redirectHelper = null;

    /**
     * Initialize Facebook SDK and redirect helper
     */
    public function init()
    {
        parent::init();

        \Facebook\FacebookSession::setDefaultApplication(
            $this->getSettings()->getId(),
            $this->getSettings()->getSecret()
        );

        $this->_redirectHelper = new \Facebook\FacebookRedirectLoginHelper(
            $this->getSettings()->getRedirect()
        );
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        return $this->_redirectHelper->getLoginUrl();
    }

    /**
     * @return string|bool
     */
    public function getAccessToken()
    {
        $session = $this->_getSession();

        if ($session) {
            return $session->getAccessToken();
        }

        return false;
    }

    /**
     * @return false|Model\User
     */
    public function getUserData()
    {
        try {
            $request = new \Facebook\FacebookRequest(
                $this->_getSession(), 'GET', '/me'
            );

            $response = $request->execute();
            $userData = $response->getResponse();

            $userModel = new Model\User();

            $userModel->setId($userData->id);
            $userModel->setEmail($userData->email);
            $userModel->setName($userData->name);
            $userModel->setGender($userData->gender);

            return $userModel;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return \Facebook\FacebookSession|null
     * @throws Exception\SessionFailure
     */
    private function _getSession()
    {
        try {
            $session = $this->_redirectHelper->getSessionFromRedirect();
            return $session;
        }
        catch(\Facebook\FacebookSDKException $e) {
            throw new Exception\SessionFailure();
        }
    }
}
