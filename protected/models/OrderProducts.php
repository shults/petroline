<?php

/**
 * Description of OrderProducts
 *
 * @author shults
 */
class OrderProducts extends CActiveRecord
{

    public function tableName()
    {
        return '{{orders_products}}';
    }

    public function primaryKey()
    {
        return array(
            'order_id',
            'product_id'
        );
    }

    public function relations()
    {
        return array(
            'item' => array(self::HAS_ONE, 'Products', array('product_id' => 'product_id'))
        );
    }

    public function rules()
    {
        return array(
            array('order_id, product_id, number_of_products', 'required'),
            array('number_of_products', 'numerical', 'min' => 1, 'allowEmpty' => true)
        );
    }
    
    /**
     * @see CActiveRecord::model()
     * 
     * @param string $className
     * @return OrderProducts
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    
    public function attributeLabels()
    {
        return array(
            'number_of_products' => Yii::t('order', 'Number of products'),
            'product_price' => Yii::t('order', 'Product price')
        );
    }

}
