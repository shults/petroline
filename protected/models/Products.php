<?php

class Products extends CActiveRecord
{

    protected static $_adminNames;

    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(self::t('Products'), self::t('product'), self::t('products'));
        }
        return self::$_adminNames;
    }

    public function tableName()
    {
        return '{{products}}';
    }

    public function primaryKey()
    {
        return 'product_id';
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function rules()
    {
        return array(
            array('title, url, category_id, price', 'required'),
            array('url', 'unique'),
            array('url', 'url', 'pattern' => '/^[a-z0-9-_]+$/'),
            array('status, store_status, description, trade_price, min_trade_order, meta_title, meta_keywords, meta_description', 'safe'),
            array('min_trade_order', 'numerical'),
            array('price, trade_price', 'length', 'max' => 8),
        );
    }

    public function attributeLabels()
    {
        return array(
            'category_id' => self::t('Сategory'),
            'status' => self::t('Active product'),
            'title' => self::t('Name'),
            'url' => 'URL',
            'price' => self::t('Price'),
            'store_status' => self::t('Store status'),
            'description' => self::t('Description'),
            'trade_price' => self::t('Trade price'),
            'min_trade_order' => self::t('Min trade order'),
            'meta_title' => self::t('Title'),
            'meta_keywords' => self::t('Meta keyowrds'),
            'meta_description' => self::t('Meta description')
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('category_id', 'dropDown'),
            array('status', 'boolean'),
            array('store_status', 'boolean'),
            array('description', 'tinymce'),
            array('meta_keywords', 'textArea'),
            array('meta_description', 'textArea'),
        );
    }

    public function category_idChoices()
    {
        $parents = Categories::model()->findAll('parent_category_id = 0');
        $data = array();
        foreach ($parents as $parent) {
            $data[$parent->category_id] = $parent->title;
            $children = Categories::model()->findAll('parent_category_id = '.$parent->category_id);
            foreach ($children as $child) {
                $data[$child->category_id] = ' - ' . $child->title;
            }
        }
        return $data;
    }

    public function getCategoryTitle()
    {
        if ($this->category) {
            return $this->category->title;
        }
    }

    public function getAllSubitems()
    {
        if (!$this->children)
            return array();
        $subitems = array();
        foreach ($this->children as $subitem) {
            $subitems[] = $subitem->page_id;
            $subitems = array_merge($subitems, $subitem->getAllSubitems());
        }
        return array_unique($subitems);
    }

    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title',
                'url',
                array(
                    'name' => 'category_id',
                    'value' => '$data->getCategoryTitle();',
                ),
                array(
                    'name' => 'store_status',
                    'value' => '$data->store_status == 1 ? "Есть" : "Нет"',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'category' => array(self::HAS_ONE, 'Categories', array('category_id' => 'category_id')),
        );
    }

    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior',
            'CTimestampBehavior' => array(
                'class' => 'system.zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            ),
            'OwnerBehaviour' => array(
                'class' => 'ycm.behaviors.OwnerBehavior'
            ),
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('products', $message, $params, $source, $language);
    }

}