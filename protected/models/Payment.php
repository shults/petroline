<?php

/**
 * Description of Зayment
 *
 * @author shults
 */
class Payment extends CActiveRecord
{

    /** @var array */
    private static $_adminNames;

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{payments}}';
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function primaryKey()
    {
        return 'payment_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param string $className
     * @return Delivery 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(
                Yii::t('app', 'Payments'),
                Yii::t('app', 'payment'),
                Yii::t('app', 'payments')
            );
        }
        return self::$_adminNames;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('title', 'required'),
            array('description, deleted', 'safe'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('app', 'Payment title'),
            'description' => Yii::t('app', 'Payment description')
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        return new CActiveDataProvider($this);
    }

    /**
     * @return array
     */
    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title'
            )
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('description', 'tinymce'),
        );
    }

    /**
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => 'deleted=:deleted AND language_id=:language_id',
            'params' => array(
                ':deleted' => 0,
                ':language_id' => Yii::app()->lang->language_id
            )
        );
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $this->saveAttributes([
            'deleted' => 1
        ]);
        return false;
    }

}
