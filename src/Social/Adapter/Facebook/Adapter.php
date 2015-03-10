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
    }

    /**
     * @return string
     */
    public function getLoginUrl()
    {
        $this->_redirectHelper = new \Facebook\FacebookRedirectLoginHelper(
            $this->getSettings()->getRedirect()
        );

        return $this->_redirectHelper->getLoginUrl(
            $this->getSettings()->getScope()
        );
    }

    /**
     * @return \Facebook\Entities\AccessToken
     * @throws Exception\CodeWasNotProvided
     */
    public function getAccessToken()
    {
        if (!($code = $this->getCode())) {
            throw new Exception\CodeWasNotProvided();
        }

        $accessToken = \Facebook\Entities\AccessToken::requestAccessToken(array(
            'code' => $code,
            'redirect_uri' => $this->getSettings()->getRedirect()
        ));

        if ($accessToken) {
            return (string)$accessToken;
        }
        return false;
    }

    /**
     * @return false|Model\User
     */
    public function getUserData()
    {
        try {
            $session = new \Facebook\FacebookSession(
                $this->getAccessToken()
            );

            $request = new \Facebook\FacebookRequest(
                $session, 'GET', '/me'
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
}
