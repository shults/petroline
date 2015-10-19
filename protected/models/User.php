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

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{users}}';
    }

    /**
     * @return string
     */
    public function primaryKey()
    {
        return 'user_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param string $className
     * @return User 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return bool
     */
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

    /**
     * @inheritdoc
     * @return array
     */
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

    /**
     * @inheritdoc
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'role' => Yii::t('app', 'Role'),
            'email' => Yii::t('app', 'E-Mail'),
            'password' => Yii::t('app', 'Password'),
            'first_name' => Yii::t('app', 'First name'),
            'last_name' => Yii::t('app', 'Last name'),
            'created_at' => Yii::t('app', 'Created at'),
            'password_repeat' => Yii::t('app', 'Confirm password')
        );
    }

}
