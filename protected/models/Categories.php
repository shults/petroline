<?php

Yii::import('ycm.controllers.CategoryController');

/**
 * @property Language $language Language of current category
 */
class Categories extends CActiveRecord
{
    const ALL_CATEGORIES_CACHE_ID = 'all_categories';

    private $_frontUrl;
    private $_imageUrl;

    /**
     * @return array
     */
    public function getAdminNames()
    {
        return [
            Yii::t('app', 'Categories'),
            Yii::t('app', 'category'),
            Yii::t('app', 'categories')
        ];
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{categories}}';
    }

    /**
     * @return string
     */
    public function primaryKey()
    {
        return 'category_id';
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria();

        if (isset($_GET[__CLASS__])) {
            $this->attributes = $_GET[__CLASS__];

            if (isset($this->title) && $this->title) {
                $criteria->addSearchCondition('title', $this->title);
            }

            if (isset($this->parent_category_id) && $this->parent_category_id !== '') {
                $criteria->addCondition('parent_category_id=:parent_category_id');
                $criteria->params = CMap::mergeArray($criteria->params, array(
                            ':parent_category_id' => $this->parent_category_id
                ));
            }
        }

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    /**
     * @param string $className
     * @return Categories
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
            array('title, url', 'required'),
            array('url', 'unique'),
            array('url', 'url', 'pattern' => '/^[a-z0-9-_]+$/'),
            array('status, parent_category_id, filename, meta_title, meta_keywords, meta_description, description', 'safe'),
            array('filename', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'parent_category_id' => Yii::t('app', 'Parent category'),
            'status' => Yii::t('app', 'Active category'),
            'title' => Yii::t('app', 'Name'),
            'url' => 'URL',
            'description' => Yii::t('app', 'Description'),
            'filename' => Yii::t('app', 'Image'),
            'meta_title' => Yii::t('app', 'Title'),
            'meta_keywords' => Yii::t('app', 'Meta keyowrds'),
            'meta_description' => Yii::t('app', 'Meta description'),
            'order' => Yii::t('app', 'Display order')
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('parent_category_id', 'dropDown'),
            array('status', 'boolean'),
            array('filename', 'image'),
            array('meta_keywords', 'textArea'),
            array('meta_description', 'textArea'),
            array('description', 'tinymce')
        );
    }

    /**
     * @return string
     */
    public function getParentTitle()
    {
        if ($this->parent) {
            return $this->parent->title;
        }
        return Yii::t('app', '-= No parent category =-');
    }

    /**
     * @return array
     */
    public function parent_category_idChoices()
    {
        if ($this->getIsNewRecord()) {
            return CHtml::listData(
                self::model()->findAll('parent_category_id=:parent_category_id', array(':parent_category_id' => 0)),
                'category_id',
                'title'
            );
        } else {
            return CHtml::listData(self::model()->findAll(
                'parent_category_id=:parent_category_id AND category_id!=:current_category_id', array(
                ':parent_category_id' => 0,
                ':current_category_id' => $this->category_id
            )), 'category_id', 'title');
        }
    }

    public function adminSearch()
    {
        return array(
            'enableSorting' => false,
            'filter' => $this,
            'columns' => array(
                array(
                    'name' => 'title',
                ),
                array(
                    'name' => 'parent_category_id',
                    'value' => function($data) {
                        return $data->getParentTitle();
                    },
                    'filter' => CMap::mergeArray(array(0 => Yii::t('app', '-= No parent category =-')), $this->parent_category_idChoices()),
                ),
                array(
                    'name' => 'order',
                    'filter' => false
                ),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{up}{down}',
                    'buttons' => array(
                        'up' => array(
                            'icon' => 'icon-arrow-up',
                            'label' => Yii::t('app', 'Up'),
                            'url' => function($data) {
                                return CHtml::normalizeUrl(array("category/orderUp", "category_id" => $data->category_id));
                            },
                            'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#objects-grid').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#objects-grid').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                        ),
                        'down' => array(
                            'icon' => 'icon-arrow-down',
                            'label' => Yii::t('app', 'Down'),
                            'url' => function($data) {
                                return CHtml::normalizeUrl(array("category/orderDown", "category_id" => $data->category_id));
                            },
                            'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#objects-grid').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#objects-grid').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                        )
                    ),
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function relations()
    {
        return array(
            'parent' => array(self::HAS_ONE, 'Categories', array('category_id' => 'parent_category_id')),
            'children' => array(self::HAS_MANY, 'Categories', array('parent_category_id' => 'category_id')),
            'language' => array(self::BELONGS_TO, 'Language', array('language_id' => 'language_id')),
        );
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
    public function defaultScope()
    {
        return array(
            'condition' => 'language_id=:language_id',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            ),
            'order' => '`parent_category_id` ASC, `order` ASC',
        );
    }

    /**
     * @return array
     */
    public function listCategoriesData()
    {
        /** @var Categories[] $categories */
        $categories = $this->findAll('parent_category_id=:parent_category_id', array(':parent_category_id' => 0));

        $categoriesList = [];

        foreach ($categories as $category) {
            $categoriesList[$category->category_id] = $category->title;

            if ($category->children) {
                $categoriesList[$category->title] = array();
                foreach ($category->children as $childCategory) {
                    $categoriesList[$category->title][$childCategory->category_id] = $childCategory->title;
                }
            }
        }

        return $categoriesList;
    }

    /**
     * @return mixed|string
     */
    public function getFullCategoryTitle()
    {
        if ($this->parent) {
            return $this->parent->title . ' >> ' . $this->title;
        }
        return $this->title;
    }

    /**
     * @throws CException
     */
    public function orderUp()
    {
        $this->order -= 1;
        $prevCategory = Categories::model()->find('`order`=:order AND `parent_category_id`=:parent_category_id', array(
            ':order' => $this->order,
            ':parent_category_id' => $this->parent_category_id
        ));

        if ($prevCategory !== null) {
            $prevCategory->order += 1;
            $prevCategory->save(false);
        }

        $this->save(false);
    }

    /**
     * @return void
     */
    public function orderDown()
    {
        $this->order += 1;

        $nextCategory = Categories::model()->find('`order`=:order AND `parent_category_id`=:parent_category_id', array(
            ':order' => $this->order,
            ':parent_category_id' => $this->parent_category_id
        ));

        if ($nextCategory) {
            $nextCategory->order -= 1;
            $nextCategory->save(false);
        }

        $this->save(false);
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->language_id = Yii::app()->lang->language_id;
            $this->order = $this->getMaxOrder();
        }
        return parent::beforeSave();
    }

    /**
     * @return int
     */
    public function getMaxOrder()
    {
        $record = $this->maxOrder()->findByAttributes([
            'parent_category_id' => $this->parent_category_id
        ]);

        return $record !== null ? $record->order + 1 : 1;
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
     * @return string
     */
    public function getFrontUrl()
    {
        if ($this->_frontUrl === null) {
            $this->_frontUrl = CHtml::normalizeUrl(array('catalog/category', 'category' => $this));
        }
        return $this->_frontUrl;
    }

    /**
     * @param $width
     * @param $height
     * @return String
     * @throws CException
     */
    public function getImageUrl($width, $height)
    {
        if ($this->_imageUrl === null) {
            $this->_imageUrl = ImageModel::model()->resize($this->getFilePath('filename'), $width, $height);
        }
        return $this->_imageUrl;
    }

    /**
     * @return mixed
     */
    public function getAllCategories()
    {
        if (!($categories = Yii::app()->cache->get(self::ALL_CATEGORIES_CACHE_ID))) {
            $categories = Categories::model()->resetScope()->enabled()->with('language')->findAll();
            Yii::app()->cache->set(self::ALL_CATEGORIES_CACHE_ID, $categories, 3600 * 24);
        }
        return $categories;
    }

    /**
     * @return string
     */
    public function getMetaTitle()
    {
        if ($this->meta_title) {
            return $this->meta_title;
        }
        
        if ($this->parent) {
            return str_replace(
                array('%category_title%', '%subcategory_title%'),
                array($this->parent->title, $this->title),
                Yii::t('seo', 'subcategory_meta_title')
            );
        } else {
            return str_replace('%title%', $this->title, Yii::t('seo', 'category_meta_title'));
        }
    }

    /**
     * @return string
     */
    public function getMetaDescription()
    {
        if ($this->meta_description) {
            return $this->meta_description;
        }
        
        if ($this->parent) {
            return str_replace(
                array('%category_title%', '%subcategory_title%'),
                array($this->parent->title, $this->title),
                Yii::t('seo', 'subcategory_meta_description')
            );
        } else {
            return str_replace('%title%', $this->title, Yii::t('seo', 'category_meta_description'));
        }
    }

}