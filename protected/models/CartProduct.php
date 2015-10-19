<?php

/**
 * Class CartProduct
 * @property Products $product
 */
class CartProduct
{
    /**
     * @var
     */
    private $product_id;

    /**
     * @var int
     */
    private $count;

    /**
     * @var Products 
     */
    private $product;

    /**
     * @param $product_id
     * @param int $count
     */
    public function __construct($product_id, $count = 1)
    {
        $this->product_id = $product_id;
        $this->count = $count;
    }

    /**
     * @param $name
     * @return mixed
     * @throws CException
     */
    public function __get($name)
    {
        $this->initProduct();
        if (property_exists(__CLASS__, $name) && method_exists($this, 'get' . $name)) {
            return $this->{'get' . $name}();
        } else {
            throw new CException('Property ' . $name . ' in ' . __CLASS__ . ' not found.');
        }
    }

    /**
     * @return null|Products
     */
    public function getProduct()
    {
        if ($this->product === null) {
            $this->initProduct();
        }
        return $this->product;
    }

    /**
     * Initializes product
     */
    private function initProduct()
    {
        $this->product = Products::model()->findByPk($this->product_id);
    }

    /**
     * @return int|string
     */
    public function getProduct_id()
    {
        return $this->product_id;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
    
    /**
     * @return int number of products in cart
     */
    public function increment()
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