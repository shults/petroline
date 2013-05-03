<?php

class CartProduct
{

    private $product_id;
    private $count;

    /**
     *
     * @var Products 
     */
    private $product;

    public function __construct($product_id, $count = 1)
    {
        $this->product_id = $product_id;
        $this->count = $count;
    }

    public function __get($name)
    {
        $this->initProuct();
        if (property_exists(__CLASS__, $name) && method_exists($this, 'get' . $name)) {
            return $this->{'get' . $name}();
        } else {
            throw new CException('Property ' . $name . ' in ' . __CLASS__ . ' not found.');
        }
    }
    
    public function getProduct()
    {
        if ($this->product === null) {
            $this->initProuct();
        }
        return $this->product;
    }
    
    private function initProuct()
    {
        $this->product = Products::model()->findByPk($this->product_id);
    }
    
    public function getProduct_id()
    {
        return $this->product_id;
    }
    
    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * 
     * @return int number of products in cart
     */
    public function inrement()
    {
        return ++$this->count;
    }
    
    /**
     * 
     * @return int number of products in cart
     */
    public function decrement()
    {
        return --$this->count;
    }
    
    
    /**
     * @see Products::getImageUrl
     * 
     * @param int $width
     * @param int $height
     * @return String
     */
    public function getImageUrl($width, $height)
    {
        return $this->getProduct()->getImageUrl($width, $height);
    }

}