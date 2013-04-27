<?php

/**
 * Description of Cart
 *
 * @author shults
 */
class Cart
{

    const PRODUCTS_COOKIE = 'cart_products';
    
    /**
     * Cart instance
     *
     * @var Cart 
     */
    private static $_instance;

    private function __construct() {}

    private static function init()
    {
        self::$_instance = new Cart;
        self::$_instance->initProducts();
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
    
    private function initProducts()
    {
        
    }

}