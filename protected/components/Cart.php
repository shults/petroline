<?php

/**
 * Description of Cart
 *
 * @author shults
 */
class Cart
{

    const CART_SESSION = 'cart_products';
    
    private static $cart_session;

    /**
     * Cart instance
     *
     * @var Cart 
     */
    private static $_instance;

    /**
     * Array of products
     * <code>
     *  array(
     *      ...
     *      array(
     *          'product_id' => $product_id,
     *          'count' => $count,
     *          'instance' => $product,
     *      ),
     *      ...
     *  )
     * </code>
     */
    private $_products;

    /**
     *
     * @var CHttpSession
     */
    private $_session;

    private function __construct(){}

    private static function init()
    {
        self::$cart_session = self::CART_SESSION . '_' . Yii::app()->lang->code;
        self::$_instance = new Cart;
        self::$_instance->initSession();
    }

    /**
     * Cart instance
     * 
     * @return Cart
     */
    public static function get()
    {
        if (self::$_instance === null) {
            self::init();
        }
        return self::$_instance;
    }

    private function initSession()
    {
        $this->_session = & Yii::app()->session;
        if ($this->_session[self::$cart_session] === null)
            $this->_session[self::$cart_session] = array();
        //$this->_session[self::PRODUCTS_SESSION] = array();
    }

    public function getProducts()
    {
        return $this->_session[self::$cart_session];
    }

    public function addProduct(Products $product)
    {
        $existsInCart = false;
        foreach ($this->_session[self::$cart_session] as $key => $cartProduct) {
            /* @var $cartProduct CartProduct */
            if ($cartProduct->product_id === $product->product_id)
                $existsInCart = true;
        }

        if (!$existsInCart) {
            $this->_session[self::$cart_session] = CMap::mergeArray($this->_session[self::$cart_session], array(new CartProduct($product->product_id)));
        }
    }

    public function incrementProduct($product_id)
    {
        /* @var $product CartProduct */
        foreach ($this->_session[self::$cart_session] as $product) {
            if ($product->product_id == $product_id) {
                $product->inrement();
            }
        }
    }

    public function decrementProduct($product_id)
    {
        /* @var $product CartProduct */
        foreach ($this->_session[self::$cart_session] as $product) {
            if ($product->product_id == $product_id) {
                if ($product->decrement() <= 0) {
                    $this->removeProduct($product_id);
                }
            }
        }
    }

    public function removeProduct($product_id)
    {
        /* @var $product CartProduct */
        $newProducts = array();
        foreach ($this->_session[self::$cart_session] as $key => $product) {
            if ($product->product_id != $product_id) {
                $newProducts[] = $product;
            }
        }
        $this->_session[self::$cart_session] = $newProducts;
    }

    public function cleanCart()
    {
        $this->_session[self::$cart_session] = array();
    }

    public function getTotalPrice($withCurrency = true)
    {
        $totalPrice = 0;
        /* @var $cartProduct CartProduct */
        foreach ($this->getProducts() as $cartProduct) {
            $totalPrice += $cartProduct->product->price * $cartProduct->count;
        }
        if ($withCurrency)
            return sprintf('%0.2f', $totalPrice) . ' ' . Yii::t('common', 'UAH');
        else
            return $totalPrice;
    }
    
    public function getIsEmpty()
    {
        if ($this->getProducts() === null || count($this->getProducts()) === 0) {
            return true;
        } else {
            return false;
        }
    }

}