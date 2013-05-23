<?php
/**
 * 
 * @property Language $language languaage of current product
 */
class Products extends CActiveRecord
{
    
    const ALL_PRODUCT_CACHE_ID = 'all_products';
    private $_frontUrl;
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
    
    public function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->order = ++$this->maxOrder()->find('category_id=:category_id', array(':category_id', $this->category_id))->order;
        }
        return parent::beforeSave();
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        $criteria->params = array();
        if (isset($_GET[__CLASS__])) {
            $this->attributes = $_GET[__CLASS__];

            if ($this->product_id) {
                $criteria->addCondition('product_id=:product_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(':product_id' => $this->product_id));
            }

            if ($this->title)
                $criteria->addSearchCondition('title', $this->title);

            if ($this->category_id) {
                $criteria->addCondition('category_id=:category_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(':category_id' => $this->category_id));
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
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

    public function rules()
    {
        return array(
            array('title, url, category_id, price', 'required'),
            array('url', 'unique'),
            array('url', 'url', 'pattern' => '/^[a-z0-9-_]+$/'),
            array('product_id, status, store_status, description, trade_price, min_trade_order, meta_title, meta_keywords, meta_description', 'safe'),
            array('min_trade_order', 'numerical'),
            array('price, trade_price', 'length', 'max' => 8),
        );
    }

    public function attributeLabels()
    {
        return array(
            'product_id' => 'ID',
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
            'meta_description' => self::t('Meta description'),
            'order' => self::t('Display order')
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
            $children = Categories::model()->findAll('parent_category_id = ' . $parent->category_id);
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
            'category' => array(self::BELONGS_TO, 'Categories', array('category_id' => 'category_id')),
            'images' => array(self::HAS_MANY, 'ProductImages', array('product_id' => 'product_id')),
            'language' => array(self::BELONGS_TO, 'Language', array('language_id' => 'language_id')),
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

    public function addProductSearch()
    {
        $criteria = new CDbCriteria;
        if ($_GET[__CLASS__]) {
            $this->attributes = $_GET[__CLASS__];

            if (isset($this->title) && $this->title !== '')
                $criteria->addSearchCondition('title', $this->title);

            if (isset($this->category_id) && $this->category_id != null) {
                $criteria->addCondition('category_id=:category_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(
                            ':category_id' => $this->category_id
                ));
            }
        }
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

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

    public function scopes()
    {
        return array(
            'maxOrder' => array(
                'select' => 'MAX(`order`) AS `order`'
            ),
            'enabled' => array(
                'condition' => 'status=:status',
                'params' => array(
                    ':status' => 1
                )
            )
        );
    }

    public function searchImages()
    {
        return new CActiveDataProvider('ProductImages', array(
            'data' => $this->images
        ));
    }

    public function getFullCategoryTitle()
    {
        if ($this->getIsNewRecord())
            throw new CException("This is new ActiveRecord model");
        return $this->category->getFullCategoryTitle();
    }

    public function orderUp()
    {
        if ($this->getIsNewRecord())
            throw new CException('You cannot order up not existance model');
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

    public function orderDown()
    {
        if ($this->getIsNewRecord())
            throw new CException('You cannot order up not existance model');
        $this->order += 1;
        if ($nextProduct = Products::model()->find('`order`=:order AND `category_id`=:category_id', array(
            ':order' => $this->order,
            ':category_id' => $this->category_id
                ))) {
            $nextProduct->order -= 1;
            $nextProduct->save(false);
        }
        $this->save(false);
    }

    public function productSearch($q, $page = 1)
    {
        $criteria = new CDbCriteria;
    }

    public function getPrice()
    {
        return $this->price . ' ' . Yii::t('common', 'UAH');
    }

    /**
     * 
     * @return String
     * @throws CException
     */
    public function getFrontUrl()
    {
        if ($this->getIsNewRecord())
            throw new CException('Method ' . __METHOD__ . ' was called to not existence record');
        if ($this->_frontUrl === null)
            $this->_frontUrl = CHtml::normalizeUrl(array('catalog/product', 'product' => $this));
        return $this->_frontUrl;
    }

    public function getImageUrl($width, $height)
    {
        if ($this->getIsNewRecord())
            throw CException("Cannot call " . __METHOD__ . " for not existence record");
        if ($this->images)
            return $this->images[0]->getImageUrl($width, $height);
        else
            return ImageModel::model()->resize(null, $width, $height);
    }
    
    public function getAllProducts()
    {
        if (!($producsts = Yii::app()->cache->get(self::ALL_PRODUCT_CACHE_ID))) {
            $producsts = $this->resetScope()->enabled()->with('language')->findAll();
            Yii::app()->cache->set(self::ALL_PRODUCT_CACHE_ID, $producsts, 3600 * 24);
        }
        return $producsts;
    }
    
    public function getMetaTitle()
    {
        if ($this->getIsNewRecord())
            throw new CException('Yu cannot call ' . __METHOD__ . ' this is new record');
        if ($this->meta_title)
            return $this->meta_title;
        return str_replace('%title%', $this->title, Yii::t('seo', 'product_meta_title'));
    }
    
    public function getMetaDescription()
    {
        if ($this->getIsNewRecord())
            throw new CException('Yu cannot call ' . __METHOD__ . ' this is new record');
        if ($this->meta_description)
            return $this->meta_description;
        return str_replace('%title%', $this->title, Yii::t('seo', 'product_meta_description'));
    }

}