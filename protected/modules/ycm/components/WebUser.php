<?php

/**
 * Description of WebUser
 *
 * @author shults
 */
class WebUser extends CWebUser
{

    /**
     *
     * @var User or null
     */
    private $_model;

    public function getRole()
    {
        if ($user = $this->getModel()) {
            return $user->role;
        }
    }

    public function getUsername()
    {
        if ($user = $this->getModel()) {
            return $user->username;
        }
    }

    public function getFirst_name()
    {
        if ($user = $this->getModel()) {
            return $user->first_name;
        }
    }

    /**
     * 
     * @return User or null if not login in
     */
    public function getModel()
    {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

}