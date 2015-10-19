<?php

/**
 * Description of Order
 *
 * @author shults
 *
 * Relations:
 * @property OrderProducts[] $products
 * @property Delivery $delivery
 * @property Payment $payment
 */
class Order extends CActiveRecord
{

    const STATUS_NOT_PROCESSED = 'not_processed';
    const STATUS_PERFOMED = 'performed';
    const STATUS_EXECUTED = 'executed';
    const STATUS_REJECTED = 'rejected';

    /** @var array */
    private static $_statusChoises;

    /**
     * @inheritdoc
     * @return string
     */
    public function primaryKey()
    {
        return 'order_id';
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{orders}}';
    }

    /**
     * @see CActiveRecord
     * 
     * @param string $className
     * @return Order
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return bool
     * @throws CException
     */
    public function beforeSave()
    {
        // set language
        if ($this->getIsNewRecord()) {
            $this->language_id = Yii::app()->lang->language_id;
            $this->incoming_date = new CDbExpression('NOW()');
        }

        if (!$this->getIsNewRecord()) {
            $this->recalculate();
        }

        return parent::beforeSave();
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'order_id' => Yii::t('app', 'ID'),
            'payment_id' => Yii::t('payment', 'Payment method'),
            'delivery_id' => Yii::t('delivery', 'Delivery method'),
            'customer_full_name' => Yii::t('app', 'Full name'),
            'customer_phone' => Yii::t('app', 'Customer phone'),
            'customer_email' => Yii::t('app', 'E-Mail'),
            'delivery_address' => Yii::t('app', 'Delivery address'),
            'status' => Yii::t('app', 'Order status'),
            'incoming_date' => Yii::t('app', 'Incoming date'),
            'total_price' => Yii::t('app', 'Total price')
        );
    }

    /**
     * @return array
     */
    public function getStatusChoises()
    {
        if (self::$_statusChoises === null) {
            self::$_statusChoises = array(
                'not_processed' => Yii::t('order', 'Not processed'),
                'performed' => Yii::t('order', 'Performed'),
                'executed' => Yii::t('order', 'Executed'),
                'rejected' => Yii::t('order', 'Rejected'),
            );
        }

        return self::$_statusChoises;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function relations()
    {
        return array(
            'products' => array(self::HAS_MANY, 'OrderProducts', array('order_id' => 'order_id')),
            'delivery' => array(self::HAS_ONE, 'Delivery', array('delivery_id' => 'delivery_id')),
            'payment' => array(self::HAS_ONE, 'Payment', array('payment_id' => 'payment_id'))
        );
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return array(
            array('payment_id, delivery_id, customer_full_name, customer_phone', 'required'),
            array('language_id, incoming_date, status', 'safe'),
            array('customer_phone', 'match', 'pattern' => '/[\+]?[0-9]{12,14}/')
        );
    }

    /**
     * @throws CDbException
     * @throws CException
     */
    private function recalculate()
    {
        if (Order::model()->findByPk($this->order_id)->status === self::STATUS_EXECUTED) {
            throw new CException(Yii::t('app', 'You cannot recalculate executed order'));
        }

        /** @var OrderProducts [] $products */
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
        if ($this->getIsNewRecord()) {
            throw new CException(Yii::t('app', 'You cannot add product into not existent order'));
        }

        $orderProduct = new OrderProducts;
        $orderProduct->order_id = $this->order_id;
        $orderProduct->product_id = $product->product_id;
        $orderProduct->product_price = $product->price;

        if ($orderProduct->validate()) {
            $orderProduct->save(false);
            $this->save(false);
            return true;
        }

        return false;
    }

    /**
     * @param $product_id
     * @return bool
     * @throws CDbException
     * @throws CException
     * @throws CHttpException
     */
    public function dropProductFromOrderByProductId($product_id)
    {
        if ($this->getIsNewRecord()) {
            throw new CException(Yii::t('app', 'You cannot drop product from not existance order'));
        }

        $orderProduct = OrderProducts::model()->byOrderId($this->order_id)->byProductId($product_id)->find();

        if ($orderProduct === null) {
            throw new CHttpException(404, Yii::t('app', 'Such product does not exist in current order'));
        }

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
        $orderProduct = OrderProducts::model()->findByPk(array(
            'order_id' => $this->order_id,
            'product_id' => $product_id
        ));

        if ($orderProduct === null) {
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
        $orderProduct = OrderProducts::model()->findByPk(array(
            'order_id' => $this->order_id,
            'product_id' => $product_id
        ));

        if ($orderProduct === null) {
            throw new CException('Product not found');
        }

        $orderProduct->number_of_products--;

        if ($orderProduct->number_of_products == 0) {
            $orderProduct->delete();
        } else {
            $orderProduct->save(false);
        }

        $this->save(false);
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        if ($this->status === self::STATUS_EXECUTED) {
            Yii::app()->user->setFlash('warning', Yii::t('order', 'You cannot delete executed order'));
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => 'language_id=:language_id',
            'order' => '`status` ASC',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            )
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = $this->getDbCriteria();

        $this->status = null;

        if (isset($_GET[__CLASS__])) {
            $this->attributes = $_GET[__CLASS__];

            if (isset($this->status) && $this->status) {
                $criteria->addCondition('status=:status');
                $criteria->params = CMap::mergeArray($criteria->params, array(':status' => $this->status));
            }

            if (isset($this->customer_full_name) && $this->customer_full_name) {
                $criteria->addSearchCondition('customer_full_name', $this->customer_full_name);
            }

            if (isset($this->customer_phone) && $this->customer_phone) {
                $criteria->addSearchCondition('customer_phone', $this->customer_phone);
            }

            if (isset($this->incoming_date) && $this->incoming_date) {
                if (preg_match('/^(\d+)\.(\d+)\.(\d+)$/i', $this->incoming_date, $matches)) {
                    $day = $matches[1];
                    $month = $matches[2];
                    $year = $matches[3];
                    $date = $year . '-' . $month . '-' . $day;//mktime(0, 0, 0, $month, $day, $year);
                    $criteria->addCondition('DATE(`incoming_date`)=:date');
                    $criteria->params = CMap::mergeArray($criteria->params, array(
                        ':date' => $date
                    ));
                }
            }
        }
        return new CActiveDataProvider($this);
    }

}
