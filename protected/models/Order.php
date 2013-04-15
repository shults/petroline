<?php

/**
 * Description of Order
 *
 * @author shults
 */
class Order extends CActiveRecord
{

    const STATUS_NOT_PROCESSED = 'not_processed';
    const STATUS_PERFOMED = 'performed';
    const STATUS_EXECUTED = 'executed';
    const STATUS_REJECTED = 'rejected';

    private static $_statusChoises;

    public function primaryKey()
    {
        return 'order_id';
    }

    public function tableName()
    {
        return '{{orders}}';
    }

    /**
     * @see CActiveRecord
     * 
     * @param type $className
     * @return Order
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function beforeSave()
    {
        if ($this->getIsNewRecord())
            $this->incoming_date = new CDbExpression('NOW()');

        if (!$this->getIsNewRecord())
            $this->recalculate();

        return parent::beforeSave();
    }

    public function attributeLabels()
    {
        return array(
            'order_id' => 'ID',
            'payment_id' => Yii::t('payment', 'Payment method'),
            'delivery_id' => Yii::t('delivery', 'Delivery method'),
            'customer_full_name' => self::t('Full name'),
            'customer_phone' => self::t('Customer phone'),
            'customer_email' => self::t('E-Mail'),
            'delivery_address' => self::t('Delivery address'),
            //not_processed => не обработан
            //rejected => отказ
            //executed => выполнен
            //performed => в процессе выполнения
            'status' => self::t('Order status'),
            'incoming_date' => self::t('Incoming date'), // дата поступления
            'total_price' => self::t('Total price')
        );
    }

    public function getStatusChoises()
    {
        if (self::$_statusChoises === null)
            self::$_statusChoises = array(
                'not_processed' => self::t('not_processed'),
                'performed' => self::t('performed'),
                'executed' => self::t('executed'),
                'rejected' => self::t('rejected'),
            );
        return self::$_statusChoises;
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('order', $message, $params, $source, $language);
    }

    public function relations()
    {
        return array(
            'products' => array(self::HAS_MANY, 'OrderProducts', array('order_id' => 'order_id')),
            'delivery' => array(self::HAS_ONE, 'Delivery', array('delivery_id' => 'delivery_id')),
            'payment' => array(self::HAS_ONE, 'Payment', array('payment_id' => 'payment_id'))
        );
    }

    public function rules()
    {
        return array(
            array('payment_id, delivery_id, customer_full_name, customer_phone', 'required'),
            array('incoming_date, status', 'safe'),
            array('customer_phone', 'match', 'pattern' => '/[\+]?[0-9]{12,14}/')
        );
    }

    private function recalculate()
    {
        if ($this->getIsNewRecord())
            throw new CException(self::t('You cannot recalculate not existance order'));
        if (Order::model()->findByPk($this->order_id)->status === self::STATUS_EXECUTED)
            throw new CException(self::t('You cannot recalculate executed order'));

        if ($products = $this->getRelated('products', true)) {
            $totalPrice = 0;
            foreach ($products as $product) {
                /* @var $product OrderProducts */
                $product->product_price = $product->item->price;
                $product->save(false);
                $totalPrice += $product->product_price * $product->number_of_products;
            }
        }

        if ($this->delivery->consider_price) {
            $totalPrice += $this->delivery->price;
        }

        $this->total_price = $totalPrice;
    }

    /**
     * This method adds Product into existance Order
     * 
     * @param Products $product
     * @return boolean true if added and false if error
     * @throws CException
     */
    public function addProductToOrder(Products $product)
    {
        if ($this->getIsNewRecord())
            throw new CException(self::t('You cannot add product into not existance order'));
        $orderProduct = new OrderProducts;
        $orderProduct->order_id = $this->order_id;
        $orderProduct->product_id = $product->product_id;
        $orderProduct->product_price = $product->price;
        if ($orderProduct->validate() && $orderProduct->save(false)) {
            $this->save(false);
            return true;
        }
        else
            return false;
    }

    public function dropProductFromOrderByProductId($product_id)
    {
        if ($this->getIsNewRecord())
            throw new CException(self::t('You cannot drop product from not existance order'));
        if (($orderProduct = OrderProducts::model()->find('order_id=:order_id AND product_id=:product_id', array(
            ':order_id' => $this->order_id,
            ':product_id' => $product_id,
                ))) === null)
            throw new CHttpException(404, Yii::t('order', 'Such product does not exist in current order'));
        if ($orderProduct->delete()) {
            $this->save(false);
            return true;
        }
        return false;
    }

    /**
     * This method increments product to order if it already exists in order
     * 
     * @param int $product_id Primary key of product
     * @throws CException
     */
    public function incrementProductById($product_id)
    {
        if ($this->getIsNewRecord())
            throw new CException(self::t('You increment product to not existance order'));
        if (($orderProduct = OrderProducts::model()->findByPk(array('order_id' => $this->order_id,
            'product_id' => $product_id))) === null) {
            throw new CException('Product not found');
        }
        $orderProduct->number_of_products++;
        $orderProduct->save(false);
        $this->save(false);
    }
    
    /**
     * This method decrement product from order 
     * If number of products equals zero than the record will be deleted
     * 
     * @param type $product_id Primary key of product
     * @throws CException
     */
    public function decrementProductById($product_id)
    {
        if ($this->getIsNewRecord())
            throw new CException(self::t('You decrement product to not existance order'));
        if (($orderProduct = OrderProducts::model()->findByPk(array('order_id' => $this->order_id,
            'product_id' => $product_id))) === null) {
            throw new CException('Product not found');
        }
        $orderProduct->number_of_products--;
        if ($orderProduct->number_of_products == 0)
            $orderProduct->delete();
        else
            $orderProduct->save(false);
        $this->save(false);
    }

    public function beforeDelete()
    {
        if ($this->status === self::STATUS_EXECUTED) {
            Yii::app()->user->setFlash('warning', Yii::t('order', 'You cannot delete executed order'));
            return false;
        }
        return parent::beforeDelete();
    }

    public function defaultScope()
    {
        return array(
            'order' => '`status` ASC'
        );
    }

}
