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

    /**
     *
     */
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

    /**
     * Initializes session
     */
    private function initSession()
    {
        $this->_session = Yii::app()->session;

        if ($this->_session[self::$cart_session] === null) {
            $this->_session[self::$cart_session] = [];
        }
    }

    /**
     * @return CartProduct[]
     */
    public function getProducts()
    {
        return $this->_session[self::$cart_session];
    }

    /**
     * @param Products $product
     */
    public function addProduct(Products $product)
    {
        $existsInCart = false;
        foreach ($this->_session[self::$cart_session] as $key => $cartProduct) {
            /* @var $cartProduct CartProduct */
            if ($cartProduct->product_id === $product->product_id) {
                $existsInCart = true;
            }
        }

        if (!$existsInCart) {
            $this->_session[self::$cart_session] = CMap::mergeArray($this->_session[self::$cart_session], [new CartProduct($product->product_id)]);
        }
    }

    /**
     * @param $product_id
     */
    public function incrementProduct($product_id)
    {
        /* @var $product CartProduct */
        foreach ($this->_session[self::$cart_session] as $product) {
            if ($product->product_id == $product_id) {
                $product->increment();
            }
        }
    }

    /**
     * @param $product_id
     */
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

    /**
     * @param $product_id
     */
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

    /**
     * Cleans cart contents
     */
    public function cleanCart()
    {
        $this->_session[self::$cart_session] = array();
    }

    /**
     * @param bool $withCurrency
     * @return int|mixed|string
     */
    public function getTotalPrice($withCurrency = true)
    {
        $totalPrice = 0;

        foreach ($this->getProducts() as $cartProduct) {
            $totalPrice += $cartProduct->product->price * $cartProduct->count;
        }

        return $withCurrency ? sprintf('%0.2f %s', $totalPrice, Yii::t('common', 'UAH')) : $totalPrice;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->getProducts() === null || count($this->getProducts()) === 0;
    }
    
    /**
     * 
     * @return int
     */
    public function getNumberOfProduct()
    {
        return count($this->getProducts());
    }

}