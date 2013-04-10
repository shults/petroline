<?php

/**
 * Description of User
 *
 * @author shults
 */
class User extends CActiveRecord
{
    
    const SCENARIO_CREATE = 'create';
    
    const SCENARIO_UPDATE = 'update';
    
    const SCENARIO_CHANGE_PASSWORD = 'change_password';

    public $password_repeat;
    
    public static $roles = array(
        'moderator' => 'Moderator',
        'administrator' => 'Administrator',
    );

    public function tableName()
    {
        return '{{users}}';
    }

    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param strint $className
     * @return User 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->password = sha1($this->password);
            $this->created_at = new CDbException('NOW()');
        }

        if ($this->getScenario() == self::SCENARIO_CHANGE_PASSWORD) {
            $this->password = sha1($this->password);
        }
        return parent::beforeSave();
    }

    public function rules()
    {
        return array(
            array('role, password, created_at', 'safe'),
            array('first_name, last_name, email', 'required'),
            array('email', 'email'),
            array('password', 'compare', 'on' => self::SCENARIO_CREATE),
            array('password, password_repeat', 'match', 'pattern' => '/^[a-z0-9]+$/', 'on' => self::SCENARIO_CREATE, 'message' => Yii::t('user', 'Field "{attribute}" must consist letters and numbers only.')),
        );
    }

    public function attributeLabels()
    {
        return array(
            'role' => self::t('Role'),
            'email' => self::t('E-Mail'),
            'password' => self::t('Password'),
            'first_name' => self::t('First name'),
            'last_name' => self::t('Last name'),
            'created_at' => self::t('Created at'),
            'password_repeat' => self::t('Confirm password')
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('user', $message, $params, $source, $language);
    }

}
