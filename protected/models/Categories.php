<?php

class Categories extends CActiveRecord
{

    protected static $_adminNames;

    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(self::t('Categories'), self::t('category'), self::t('categories'));
        }
        return self::$_adminNames;
    }

    public function tableName()
    {
        return '{{categories}}';
    }

    public function primaryKey()
    {
        return 'category_id';
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
            array('title, url', 'required'),
            array('url', 'unique'),
            array('url', 'url', 'pattern' => '/^[a-z0-9-_]+$/'),
            array('status, parent_category_id, filename, meta_title, meta_keywords, meta_description', 'safe'),
            array('filename', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'parent_category_id' => self::t('Parent category'),
            'status' => self::t('Active category'),
            'title' => self::t('Name'),
            'url' => 'URL',
            'filename' => self::t('image'),
            'meta_title' => self::t('Title'),
            'meta_keywords' => self::t('Meta keyowrds'),
            'meta_description' => self::t('Meta description')
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('parent_category_id', 'dropDown'),
            array('status', 'boolean'),
            array('filename', 'image'),
            array('meta_keywords', 'textArea'),
            array('meta_description', 'textArea'),
        );
    }

    public function getParentTitle()
    {
        if ($this->parent) {
            return $this->parent->title;
        }
        return self::t('-= No parent category =-');
    }

    public function parent_category_idChoices()
    {
        if ($this->getIsNewRecord()) {
            return CHtml::listData(
                            self::model()->findAll(
                                    'parent_category_id=:parent_category_id', array(':parent_category_id' => 0)), 'category_id', 'title');
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
            'columns' => array(
                array(
                    'name' => 'image',
                    'value' => 'CHtml::image($data->getFileUrl("filename"), "", array("width" => 150));',
                    'type' => 'raw',
                ),
                'title',
                'url',
                array(
                    'name' => 'parent_category_id',
                    'value' => '$data->getParentTitle();',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
            'parent' => array(self::HAS_ONE, 'Categories', array('category_id' => 'parent_category_id')),
            'children' => array(self::HAS_MANY, 'Categories', array('parent_category_id' => 'category_id'))
        );
    }

    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior'
        );
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('categories', $message, $params, $source, $language);
    }

    public function defaultScope()
    {
        return array(
            'condition' => 'language_id=:language_id',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            ),
        );
    }

}