<?php

/**
 * Description of Зayment
 *
 * @author shults
 */
class Payment extends CActiveRecord
{

    private static $_adminNames;

    public function tableName()
    {
        return '{{payments}}';
    }

    public function primaryKey()
    {
        return 'payment_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param strint $className
     * @return Delivery 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(self::t('Payments'), self::t('payment'), self::t('payments'));
        }
        return self::$_adminNames;
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('description, deleted', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => self::t('Payment title'),
            'description' => self::t('Payment description')
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('payment', $message, $params, $source, $language);
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title'
            )
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('description', 'tinymce'),
        );
    }

    public function defaultScope()
    {
        return array(
            'condition' => 'deleted=:deleted',
            'params' => array(
                ':deleted' => 0
            )
        );
    }

    public function beforeDelete()
    {
        $this->deleted = 1;
        $this->save(false);
        return false;
    }

}
