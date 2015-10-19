<?php

/**
 * Description of OrderProducts
 *
 * @author shults
 *
 * Relations:
 * @property Products $item
 */
class OrderProducts extends CActiveRecord
{

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{orders_products}}';
    }

    /**
     * @return array
     */
    public function primaryKey()
    {
        return array(
            'order_id',
            'product_id'
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'item' => array(self::HAS_ONE, 'Products', array('product_id' => 'product_id'))
        );
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'number_of_products' => Yii::t('app', 'Number of products'),
            'product_price' => Yii::t('app', 'Product price')
        );
    }

    /**
     * Modifies internal DB criteria object.
     * @param int $order_id
     * @return $this
     */
    public function byOrderId($order_id)
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'order_id=:order_id',
            'params' => array(
                ':order_id' => $order_id
            )
        ]);
        return $this;
    }

    /**
     * Modifies internal DB criteria object.
     * @param int $product_id
     * @return $this
     */
    public function byProductId($product_id)
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'product_id=:product_id',
            'params' => array(
                ':product_id' => $product_id
            )
        ]);
        return $this;
    }

}
