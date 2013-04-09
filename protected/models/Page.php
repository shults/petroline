<?php

class Page extends CActiveRecord
{

	public $adminNames = array('Страницы', 'страницу', 'страницы');

	public function tableName()
	{
		return '{{pages}}';
	}

	public function primaryKey()
	{
		return 'page_id';
	}

	public function search()
	{
		$criteria = new CDbCriteria(array(
			
		));
		if ($_GET['Page']) {
			$this->attributes = $_GET['Page'];
			if ($_GET['Page']['meta_title']) {
				$criteria->addSearchCondition('meta_title', $_GET['Page']['meta_title']);
			}
		}
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria
		));
	}

	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	public function beforeValidate()
	{
		return true;
	}

	public function rules()
	{
		return array(
			array('url', 'unique'),
			array('title,url', 'required'),
			array('url', 'url', 'pattern' => '/[a-z0-9-_\/]+/is'),
			array('enabled,text,meta_title,meta_keywords,meta_description,created_at,updated_at', 'safe')
		);
	}

	public function attributeLabels()
	{
		return array(
			'enabled' => 'Страница включена',
			'title' => 'Название',
			'url' => 'URL',
			'text' => 'Текст',
			'meta_title' => 'Заголовок',
			'meta_keywords' => 'Ключевые сдлова',
			'meta_description' => 'Описание (meta)'
		);
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

	public function attributeWidgets()
	{
		return array(
			array('text', 'wysiwyg'),
			array('enabled', 'boolean'),
			array('meta_keywords', 'textArea'),
			array('meta_description', 'textArea'),
		);
	}

	public function getFullTitle()
	{
		return $this->title;
	}

	public function getFullUrl()
	{
		return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

	public function adminSearch()
	{
		return array(
			'columns' => array(
				array(
					'name' => 'title',
					'value' => '$data->getFullTitle();'
				),
				'meta_title',
				array(
					'name' => 'url',
					'value' => 'CHtml::link($data->getFullUrl(), $data->getFullUrl(), array("target" => "_blank"))',
					'type' => 'raw',
				)
			),
		);
	}

	public function relations()
	{
		return array(
				//'parent' => array(self::HAS_ONE, 'Page', array('page_id' => 'parent_page_id')),
				//'children' => array(self::HAS_MANY, 'Page', array('parent_page_id' => 'page_id'))
		);
	}

	public function behaviors()
	{
		return array(
			'timestamp' => array(
				'class' => 'zii.behaviors.CTimestampBehavior',
				'createAttribute' => 'created_at',
				'updateAttribute' => 'updated_at'
			)
		);
	}

}