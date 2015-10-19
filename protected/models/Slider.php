<?php

/**
 * Relations:
 * @property Products $product Related product
 */
class Slider extends CActiveRecord
{

    private $_adminNames;

    /**
     * @param string $className
     * @return Slider
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior'
        );
    }

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if ($this->_adminNames === null) {
            $this->_adminNames = array(
                Yii::t('app', 'Slider module'),
                Yii::t('app', 'slide'),
                Yii::t('app', 'slide')
            );
        }

        return $this->_adminNames;
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{product_slider}}';
    }

    /**
     * @return string
     */
    public function primaryKey()
    {
        return 'product_id';
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
    public function adminSearch()
    {
        return array(
            'enableSorting' => false,
            'columns' => array(
                array(
                    'header' => Yii::t('app', 'Image'),
                    'type' => 'raw',
                    'filter' => false,
                    'value' => function(Slider $model) {
                        return CHtml::image($model->getImageUrl(100, 100));
                    }
                ),
                array(
                    'name' => 'product.title',
                ),
                array(
                    'name' => 'order',
                    'filter' => false
                )
            )
        );
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->order = ++$this->maxOrder()->find()->order;
            $this->language_id = Yii::app()->lang->language_id;
        }
        return parent::beforeSave();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('product_id', 'required'),
            array('language_id, order, title', 'safe'),
            array('product_id', 'unique')
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'product_id' => Yii::t('app', 'Product'),
            'title' => Yii::t('app', 'Slide name'),
            'order' => Yii::t('app', 'Sort order')
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('product_id', 'chosen'),
        );
    }

    /**
     * @return array
     */
    public function product_idChoices()
    {
        return CHtml::listData(Products::model()->enabled()->findAll(), 'product_id', 'title');
    }

    /**
     * @return $this
     */
    public function maxOrder()
    {
        $this->getDbCriteria()->mergeWith(array(
            'select' => 'MAX(`order`) AS `order`',
        ));
        return $this;
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
            'order' => '`order` ASC',
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'product' => array(self::HAS_ONE, 'Products', array('product_id' => 'product_id'))
        );
    }

    /**
     * @return String url of image
     */
    public function getImageUrl($widht, $height)
    {
        return $this->product->getImageUrl($widht, $widht);
    }

    /**
     * @return string
     */
    public function getTitle() 
    {
        return $this->title ? $this->title : $this->product->title;
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->product->getPrice();
    }

    /**
     * @return String
     */
    public function getFrontUrl()
    {
        return $this->product->getFrontUrl();
    }

}