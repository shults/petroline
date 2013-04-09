<?php

/**
 * Description of PhpAuthManager
 *
 * @author shults
 */
class PhpAuthManager extends CPhpAuthManager
{

    public function init()
    {

        if ($this->authFile === null) {
            $this->authFile = Yii::getPathOfAlias('application.data.auth') . '.php';
        }

        parent::init();

        if (!Yii::app()->user->isGuest) {
            $this->assign(Yii::app()->user->role, Yii::app()->user->id);
        }
    }

}
