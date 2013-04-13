<?php

/**
 * Description of Delivery
 *
 * @author shults
 */
class Delivery extends CActiveRecord
{

    private static $_adminNames;

    public function tableName()
    {
        return '{{deliveries}}';
    }

    public function primaryKey()
    {
        return 'delivery_id';
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
            self::$_adminNames = array(self::t('Deliveries'), self::t('delivery'), self::t('deliveries'));
        }
        return self::$_adminNames;
    }

    public function rules()
    {
        return array(
            array('title', 'required'),
            array('price', 'numerical', 'min' => 0, 'allowEmpty' => true),
            array('description, consider_price, show_order_comment, order_comment, deleted', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'title' => self::t('Delivery title'),
            'consider_price' => self::t('Consider price'),
            'price' => self::t('Price'),
            'show_order_comment' => self::t('Show order comment'),
            'order_comment' => self::t('Order comment'),
            'description' => self::t('Delivery description')
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('delivery', $message, $params, $source, $language);
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
            array('consider_price', 'boolean'),
            array('show_order_comment', 'boolean')
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
