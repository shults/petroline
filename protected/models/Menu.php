<?php

class Menu extends CActiveRecord
{

	public $adminNames = array('Пункты меню', 'пункт меню', 'пункты меню');

	public function tableName()
	{
		return '{{menu}}';
	}

	public function primaryKey()
	{
		return 'menu_id';
	}

	public function defaultScope()
	{
		return array(
			'order' => '`order` ASC',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria(array(
			'order' => 'parent_menu_id ASC, `order` ASC'
		));
		if ($_GET['Menu']) {
			$this->attributes = $_GET['Menu'];
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
		if (!$this->isNewRecord && $this->parent_menu_id == $this->menu_id) {
			$this->addError('parent_menu_id', 'Menu item cannot be parent for itself');
			return false;
		}
		return parent::beforeValidate();
	}

	public function beforeSave()
	{
		if ($this->home == 1) {
			$this->updateAll(array(
				'home' => 0,
			));
		}
		return parent::beforeSave();
	}

	public function rules()
	{
		return array(
			array('url', 'unique'),
			array('anchour,url', 'required'),
			array('url', 'url', 'pattern' => '/[a-z0-9_-]+/is'),
			array('parent_menu_id,home,order', 'safe')
		);
	}

	public function attributeLabels()
	{
		return array(
			'enabled' => 'Пункт меню включен',
			'home' => 'Главный пункт меню',
			'parent_menu_id' => 'Родительский пункт меню',
			'anchour' => 'Анкор',
			'url' => 'URL',
			'order' => 'Порядок отображения'
		);
	}

	public function getAllSubitems()
	{
		if (!$this->children)
			return array();
		$subitems = array();
		foreach ($this->children as $subitem) {
			$subitems[] = $subitem->menu_id;
			$subitems = array_merge($subitems, $subitem->getAllSubitems());
		}
		return array_unique($subitems);
	}

	public function parent_menu_idChoices()
	{
		if ($this->isNewRecord) {
			$parentMenuItems = $this->findAll();
		} else {
			if (($subitems = $this->getAllSubitems()) !== array()) {
				$parentMenuItems = $this->findAll('menu_id!=:menu_id AND menu_id NOT IN(' . implode(',', $subitems) . ')', array(':menu_id' => $this->menu_id));
			} else {
				$parentMenuItems = $this->findAll('menu_id!=:menu_id', array(':menu_id' => $this->menu_id));
			}
		}
		return array_merge(array(
			'0' => '-= Нет родительского меню =-'
				), CHtml::listData($parentMenuItems, 'menu_id', 'anchour'));
	}

	public function attributeWidgets()
	{
		return array(
			array('parent_menu_id', 'dropDown'),
			array('enabled', 'boolean'),
			array('home', 'boolean')
		);
	}

	public function getFullAnchour()
	{
		if ($this->parent && $_anchour = $this->parent->getFullAnchour()) {
			return $_anchour . ' > ' . $this->anchour;
		}
		else
			return $this->anchour;
	}

	public function getUrl()
	{
		if ($this->home == 1)
			return '/';
		if ($this->parent)
			return $parent->getUrl() . '/' . $this->url;
		return '/' . $this->url;
	}

	public function getFullUrl()
	{
		if ($this->home == 1)
			return Yii::app()->request->getBaseUrl(true);
		if ($this->parent && $_url = $this->parent->getFullUrl()) {
			return $_url . '/' . $this->url;
		}
		else
			return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

	public function scopes()
	{
		return array(
			'enabled' => array(
				'condition' => 'enabled=:enabled',
				'params' => array(
					':enabled' => 1
				)
			)
		);
	}

	public function getIsActive()
	{
		if (Yii::app()->request->getUrl() == '/' && $this->home == 1)
			return true;
		if ($this->getFullUrl() == Yii::app()->request->getBaseUrl(true) . Yii::app()->request->getUrl())
			return true;
		return false;
	}

	public function adminSearch()
	{
		return array(
			'columns' => array(
				array(
					'name' => 'anchour',
					'value' => '$data->getFullAnchour();'
				),
				array(
					'name' => 'url',
					'value' => 'CHtml::link($data->getFullUrl(), $data->getFullUrl(), array("target" => "_blank"))',
					'type' => 'raw',
				),
				'order'
			),
		);
	}

	public function relations()
	{
		return array(
			'parent' => array(self::HAS_ONE, 'Menu', array('menu_id' => 'parent_menu_id')),
			'children' => array(self::HAS_MANY, 'Menu', array('parent_menu_id' => 'menu_id'))
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