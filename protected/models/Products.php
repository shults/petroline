<?php
/**
 *
 * Relations:
 * @property Language $language language of current product
 * @property Categories $category
 * @property ProductImages[] $images
 */
class Products extends CActiveRecord
{
    
    const ALL_PRODUCT_CACHE_ID = 'all_products';
    private $_frontUrl;
    protected static $_adminNames;

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(Yii::t('app', 'Products'), Yii::t('app', 'product'), Yii::t('app', 'products'));
        }
        return self::$_adminNames;
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{products}}';
    }

    /**
     * @return string
     */
    public function primaryKey()
    {
        return 'product_id';
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->order = ++$this->maxOrder()->find('category_id=:category_id', array(':category_id', $this->category_id))->order;
        }
        return parent::beforeSave();
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = $this->getDbCriteria();

        if (isset($_GET[__CLASS__])) {
            $this->attributes = $_GET[__CLASS__];

            if ($this->product_id) {
                $criteria->addCondition('product_id=:product_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(':product_id' => $this->product_id));
            }

            if ($this->title) {
                $criteria->addSearchCondition('title', $this->title);
            }

            if ($this->category_id) {
                $criteria->addCondition('category_id=:category_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(':category_id' => $this->category_id));
            }
        }

        return new CActiveDataProvider($this);
    }

    /**
     * 
     * @param String $className
     * @return Products
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('title, url, category_id, price', 'required'),
            array('url', 'unique'),
            array('url', 'url', 'pattern' => '/^[a-z0-9-_]+$/'),
            array('product_id, status, store_status, description, trade_price, min_trade_order, meta_title, meta_keywords, meta_description, display_ajax', 'safe'),
            array('min_trade_order', 'numerical'),
            array('price, trade_price', 'length', 'max' => 8),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'product_id' => 'ID',
            'category_id' => Yii::t('app', 'Сategory'),
            'status' => Yii::t('app', 'Active product'),
            'title' => Yii::t('app', 'Name'),
            'url' => Yii::t('app', 'URL'),
            'price' => Yii::t('app', 'Price'),
            'store_status' => Yii::t('app', 'Store status'),
            'description' => Yii::t('app', 'Description'),
            'trade_price' => Yii::t('app', 'Trade price'),
            'min_trade_order' => Yii::t('app', 'Min trade order'),
            'meta_title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta keyowrds'),
            'meta_description' => Yii::t('app', 'Meta description'),
            'order' => Yii::t('app', 'Display order'),
            'display_ajax' => Yii::t('app', 'Display AJAX')
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('category_id', 'dropDown'),
            array('status', 'boolean'),
            array('store_status', 'boolean'),
            array('display_ajax', 'boolean'),
            array('description', 'tinymce'),
            array('meta_keywords', 'textArea'),
            array('meta_description', 'textArea'),
        );
    }

    /**
     * Todo: move to helper
     * @return array
     */
    public function category_idChoices()
    {
        $parents = Categories::model()->findAll('parent_category_id = 0');
        $data = array();

        foreach ($parents as $parent) {
            $data[$parent->category_id] = $parent->title;
            $children = Categories::model()->findAll('parent_category_id = ' . $parent->category_id);
            foreach ($children as $child) {
                $data[$child->category_id] = ' - ' . $child->title;
            }
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getCategoryTitle()
    {
        if ($this->category) {
            return $this->category->title;
        }
    }

    /**
     * @return array
     */
    public function getAllSubitems()
    {
        if (!$this->children) {
            return array();
        }

        $subitems = array();

        foreach ($this->children as $subitem) {
            $subitems[] = $subitem->page_id;
            $subitems = array_merge($subitems, $subitem->getAllSubitems());
        }

        return array_unique($subitems);
    }

    /**
     * @return array
     */
    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title',
                'url',
                array(
                    'name' => 'category_id',
                    'value' => function(Products $model) {
                        return $model->getCategoryTitle();
                    },
                ),
                array(
                    'name' => 'store_status',
                    'value' => function($data) {
                        return $data->store_status == 1 ? Yii::t('app', 'Presents') : Yii::t('app', 'Does not present');
                    },
               ),
            ),
        );
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function relations()
    {
        return array(
            'category' => array(self::BELONGS_TO, 'Categories', array('category_id' => 'category_id')),
            'images' => array(self::HAS_MANY, 'ProductImages', array('product_id' => 'product_id')),
            'language' => array(self::BELONGS_TO, 'Language', array('language_id' => 'language_id')),
        );
    }

    /**
     * @inheritdoc
     * @return array
     */
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

    /**
     * @return CActiveDataProvider
     */
    public function addProductSearch()
    {
        $criteria = $this->getDbCriteria();

        if ($_GET[__CLASS__]) {

            $this->attributes = $_GET[__CLASS__];

            if (isset($this->title) && $this->title !== '') {
                $criteria->addSearchCondition('title', $this->title);
            }

            if (isset($this->category_id) && $this->category_id != null) {
                $criteria->addCondition('category_id=:category_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(
                            ':category_id' => $this->category_id
                ));
            }
        }

        return new CActiveDataProvider($this);
    }

    /**
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => '`language_id`=:language_id',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            ),
            'order' => '`category_id` ASC, `order` ASC',
        );
    }

    /**
     * @return $this
     */
    public function maxOrder()
    {
        $this->getDbCriteria()->mergeWith([
            'select' => 'MAX(`order`) AS `order`'
        ]);
        return $this;
    }

    /**
     * @return $this
     */
    public function enabled()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'status=:status',
            'params' => array(
                ':status' => 1
            )
        ]);
        return $this;
    }

    /**
     * @return CActiveDataProvider
     */
    public function searchImages()
    {
        return new CActiveDataProvider('ProductImages', array(
            'data' => $this->images
        ));
    }

    /**
     * @return mixed
     */
    public function getFullCategoryTitle()
    {
        return $this->category ? $this->category->getFullCategoryTitle() : 'null';
    }

    /**
     * Increments `order` property and saves record
     */
    public function orderUp()
    {
        $this->order -= 1;

        if ($prevProduct = Products::model()->find('`order`=:order AND `category_id`=:category_id', array(
            ':order' => $this->order,
            ':category_id' => $this->category_id
                ))) {
            $prevProduct->order += 1;
            $prevProduct->save(false);
        }

        $this->save(false);
    }

    /**
     * Decrements `order` property and saves record
     */
    public function orderDown()
    {
        $this->order += 1;

        $nextProduct = Products::model()->find('`order`=:order AND `category_id`=:category_id', array(
            ':order' => $this->order,
            ':category_id' => $this->category_id
        ));

        if ($nextProduct !== null) {
            $nextProduct->order -= 1;
            $nextProduct->save(false);
        }

        $this->save(false);
    }

    /**
     * @return string
     */
    public function getPrice()
    {
        return $this->price . ' ' . Yii::t('common', 'UAH');
    }

    /**
     * Returns url
     * @return String
     * @throws CException
     */
    public function getFrontUrl()
    {
        if ($this->_frontUrl === null) {
            $this->_frontUrl = CHtml::normalizeUrl(array('catalog/product', 'product' => $this));
        }
        return $this->_frontUrl;
    }

    /**
     * Returns (and resizes if needed) image url.
     * If there not images, dummy image url will be returned.
     *
     * @param $width
     * @param $height
     * @return String
     * @throws
     */
    public function getImageUrl($width, $height)
    {
        if ($this->images) {
            return $this->images[0]->getImageUrl($width, $height);
        }

        return ImageModel::model()->resize(null, $width, $height);
    }

    /**
     * @return Products[]
     */
    public function getAllProducts()
    {
        if (!($producsts = Yii::app()->cache->get(self::ALL_PRODUCT_CACHE_ID))) {
            $producsts = $this->resetScope()->enabled()->with('language')->findAll();
            Yii::app()->cache->set(self::ALL_PRODUCT_CACHE_ID, $producsts, 3600 * 24);
        }

        return $producsts;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }

        return str_replace('%title%', $this->title, Yii::t('seo', 'product_meta_title'));
    }

    /**
     * @return mixed
     */
    public function getMetaDescription()
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }

        return str_replace('%title%', $this->title, Yii::t('seo', 'product_meta_description'));
    }

}