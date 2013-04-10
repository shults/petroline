<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ChangePasswordForm
 *
 * @author shults
 */
class ChangePasswordForm extends CFormModel
{

    public $password;
    public $password_repeat;
    public $old_password;

    public function rules()
    {
        return array(
            array('password, password_repeat, old_password', 'required'),
            array('password', 'compare'),
            array('password, password_repeat', 'match', 'pattern' => '/^[a-z0-9]+$/', 'message' => Yii::t('user', 'Field "{attribute}" must consist letters and numbers only.')),
            array('password', 'length', 'allowEmpty' => false, 'min' => 6),
            array('old_password', 'checkOldPassword')
        );
    }

    public function checkOldPassword($attribute, $params)
    {
        $currentUser = Yii::app()->user->getModel();
        if ($currentUser->password !== sha1($this->$attribute)) {
            $this->addError($attribute, Yii::t('user', 'Old password is incorret.'));
        }
    }

    public function attributeLabels()
    {
        return array(
            'password' => Yii::t('user', 'New password'),
            'password_repeat' => Yii::t('user', 'New password confirm'),
            'old_password' => Yii::t('user', 'Old password')
        );
    }

}
