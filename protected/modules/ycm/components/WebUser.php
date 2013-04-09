<?php

/**
 * Description of WebUser
 *
 * @author shults
 */
class WebUser extends CWebUser
{

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

    private function getModel()
    {
        if (!$this->isGuest && $this->_model === null) {
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

}