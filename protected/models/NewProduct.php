<?php

/**
 * Description of NewProduct
 *
 * @author shults
 *
 * Relations:
 * @property Products $item
 */
class NewProduct extends CActiveRecord
{

    /**
     * @see CActiveRecord::model()
     * 
     * @param String $className
     * @return NewProduct
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{new_products}}';
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function primaryKey()
    {
        return 'product_id';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('product_id', 'required'),
            array('product_id', 'unique', 'message' => Yii::t('catalog', 'This product is already added')),
            array('order, language_id', 'safe')
        );
    }

    /**
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => 'language_id=:language_id',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            ),
            'order' => '`order` ASC'
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
     * @return bool
     */
    public function beforeSave()
    {
        // set language if new property
        if ($this->getIsNewRecord()) {
            $this->language_id = Yii::app()->lang->language_id;
        }

        // increase order
        if ($this->getIsNewRecord()) {
            $this->order = ++$this->maxOrder()->find()->order;
        }

        return parent::beforeSave();
    }

    /**
     * @return array
     */
    public function scopes()
    {
        return array(
            'maxOrder' => array(
                'select' => 'MAX(`order`) AS `order`',
                'condition' => 'language_id=:language_id',
                'params' => array(
                    ':language_id' => Yii::app()->lang->language_id
                ),
            )
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        return new CActiveDataProvider($this);
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'order' => Yii::t('app', 'Display order'),
            'product_id' => Yii::t('app', 'Product')
        );
    }

    /**
     * @throws CException
     */
    public function orderUp()
    {
        $this->order -= 1;

        if ($prevProduct = NewProduct::model()->find('`order`=:order', array(':order' => $this->order))) {
            $prevProduct->order += 1;
            $prevProduct->save(false);
        }

        $this->save(false);
    }

    /**
     * @throws CException
     */
    public function orderDown()
    {
        $this->order += 1;

        if (($nextProduct = NewProduct::model()->find('`order`=:order', array(':order' => $this->order)))) {
            $nextProduct->order -= 1;
            $nextProduct->save(false);
        }

        $this->save(false);
    }
    
    /**
     * Return image url
     * 
     * @param int $width
     * @param int $height
     * @return String url path to 
     */
    public function getImageUrl($width, $height)
    {
        return $this->item->getImageUrl($width, $height);
    }

}
