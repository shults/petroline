<?php

class ProductImages extends CActiveRecord
{
    public $image;
    protected static $_adminNames;

    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(self::t('Products'), self::t('product'), self::t('products'));
        }
        return self::$_adminNames;
    }

    public function tableName()
    {
        return '{{product_images}}';
    }

    public function primaryKey()
    {
        return 'image_id';
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('product_id, filepath', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'image' => self::t('Image'),
        );
    }

    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior',
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('productImages', $message, $params, $source, $language);
    }

}