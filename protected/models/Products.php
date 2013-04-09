<?php

class Products extends CActiveRecord
{

    public $adminNames = array('Категории', 'категорию', 'категории');

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

    public function beforeValidate()
    {
        return true;
    }

    /*public function beforeSave()
    {
        if ($image = CUploadedFile::getInstance(self::model(), 'image')) {
            $extName = $image->getExtensionName();
            $path = Yii::app()->getBasePath() . "/../uploads/products/";
            $filename = $this->url . "." . $extName;

            if (!is_dir($path)) {
                mkdir($path);
                chmod($path, 0777);
            }
            if (is_file($image->getTempName())) {
                if (rename($image->getTempName(), $path . $filename)) {
                    chmod($path . $filename, 0777);
                    $this->filename = $filename;
                }
            }
        }
        parent::beforeSave();
    }*/

    public function rules()
    {
        return array(
            array('url', 'unique'),
            array('title, url', 'required'),
            array('url', 'url', 'pattern' => '/[a-z0-9-_\/]+/is'),
            array('status, parent_product_id, filename, meta_title, meta_keywords, meta_description', 'safe'),
            array('filename', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'parent_product_id' => 'Родитель',
            'status' => 'Категория активна',
            'title' => 'Название',
            'url' => 'URL',
            'filename' => 'Изображение',
            'meta_title' => 'Заголовок',
            'meta_keywords' => 'Ключевые слова',
            'meta_description' => 'Описание (meta)'
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('parent_product_id', 'dropDown'),
            array('status', 'boolean'),
            array('filename', 'image'),
            array('meta_keywords', 'textArea'),
            array('meta_description', 'textArea'),
        );
    }

    public function getParentTitleByParentId($parentId)
    {
        return self::model()->find('product_id=:product_id', array(':product_id' => $parentId))->title;
    }

    public function parent_product_idChoices()
    {
        return CHtml::listData(self::model()->findAll(), 'product_id', 'title');
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
                    'name' => 'parent_product_id',
                    'value' => '$data->parent_product_id == 0 ? "Нет" : $data->getParentTitleByParentId($data->parent_product_id);',
                ),
            ),
        );
    }

    public function relations()
    {
        return array(
                //'parent' => array(self::HAS_ONE, 'Products', array('page_id' => 'parent_page_id')),
                //'children' => array(self::HAS_MANY, 'Products', array('parent_page_id' => 'page_id'))
        );
    }
    
    public function behaviors() {
		return array(
			'FileBehavior' => 'application.behaviors.FileBehavior'
		);
	}

}