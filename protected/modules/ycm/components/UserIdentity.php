<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{

    private $_id;

    /**
     * Authenticates a user.
     *
     * @throws CHttpException
     * @return boolean whether authentication succeeds.
     */
    public function authenticate()
    {

        if (!$dbUser = User::model()->find('email=:username', array(':username' => $this->username))) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
            return !$this->errorCode;
        }

        if ($dbUser->password != sha1(trim($this->password))) {
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        } else {
            $this->_id = $dbUser->user_id;
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }

}