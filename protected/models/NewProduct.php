<?php

/**
 * Description of NewProduct
 *
 * @author shults
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

    public function tableName()
    {
        return '{{new_products}}';
    }

    public function primaryKey()
    {
        return 'product_id';
    }

    public function rules()
    {
        return array(
            array('product_id', 'required'),
            array('product_id', 'unique', 'message' => Yii::t('catalog', 'This product is already added')),
            array('order, language_id', 'safe')
        );
    }

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

    public function relations()
    {
        return array(
            'item' => array(self::HAS_ONE, 'Products', array('product_id' => 'product_id'))
        );
    }

    public function beforeSave()
    {
        if ($this->getIsNewRecord())
            $this->language_id = Yii::app()->lang->language_id;
        if ($this->getIsNewRecord()) {
            $this->order = ++$this->maxOrder()->find()->order;
        }
        return parent::beforeSave();
    }

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

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public function attributeLabels()
    {
        return array(
            'order' => self::t('Display order'),
            'product_id' => self::t('Product')
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('catalog', $message, $params, $source, $language);
    }

    public function orderUp()
    {
        if ($this->getIsNewRecord())
            throw new CException('You cannot order up not existance model');
        $this->order -= 1;
        if ($prevProduct = NewProduct::model()->find('`order`=:order', array(':order' => $this->order))) {
            $prevProduct->order += 1;
            $prevProduct->save(false);
        }
        $this->save(false);
    }

    public function orderDown()
    {
        if ($this->getIsNewRecord())
            throw new CException('You cannot order up not existance model');
        $this->order += 1;
        if (($nextProduct = NewProduct::model()->find('`order`=:order', array(':order' => $this->order)))) {
            $nextProduct->order -= 1;
            $nextProduct->save(false);
        }
        $this->save(false);
    }

}
