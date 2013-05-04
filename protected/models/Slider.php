<?php
/**
 * @property Products $product Releted product
 */
class Slider extends CActiveRecord
{

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior'
        );
    }

    public $adminNames = array('Модуль слайдер', 'слайд', 'слайд');

    public function tableName()
    {
        return '{{product_slider}}';
    }

    public function primaryKey()
    {
        return 'product_id';
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public function adminSearch()
    {
        return array(
            'enableSorting' => false,
            'columns' => array(
                array(
                    'header' => 'Изображение',
                    'value' => 'CHtml::image($data->getImageUrl(100, 100))',
                    'type' => 'raw',
                    'filter' => false
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

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            $this->order = ++$this->maxOrder()->find()->order;
            $this->language_id = Yii::app()->lang->language_id;
        }
        return parent::beforeSave();
    }

    public function rules()
    {
        return array(
            array('product_id', 'required'),
            array('language_id, order, title', 'safe'),
            array('product_id', 'unique')
        );
    }

    public function attributeLabels()
    {
        return array(
            'product_id' => 'Товар',
            'title' => 'Название слайда',
            'order' => 'Порядок отображения'
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('product_id', 'chosen'),
        );
    }
    
    public function product_idChoices()
    {
        return CHtml::listData(Products::model()->enabled()->findAll(), 'product_id', 'title');
    }

    public function scopes()
    {
        return array(
            'maxOrder' => array(
                'select' => 'MAX(`order`) AS `order`',
            ),
        );
    }

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
    
    public function relations()
    {
        return array(
            'product' => array(self::HAS_ONE, 'Products', array('product_id' => 'product_id'))
        );
    }

    /**
     * 
     * @return String url of image
     */
    public function getImageUrl($widht, $height)
    {
        return $this->product->getImageUrl($widht, $widht);
    }
    
    public function getTitle() 
    {
        if ($this->title)
            return $this->title;
        else
            return $this->product->title;
    }
    
    public function getPrice()
    {
        return $this->product->getPrice();
    }
    
    public function getFrontUrl()
    {
        return $this->product->getFrontUrl();
    }

}