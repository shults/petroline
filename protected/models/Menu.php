<?php

/**
 * Class Menu
 *
 * Relations:
 * @property Menu $parent
 * @property Menu children
 */
class Menu extends CActiveRecord
{

    /** @var array */
    private $_adminNames;

    /**
     * Returns array of labels
     * @return array
     */
    public function getAdminNames()
    {
        if ($this->_adminNames === null) {
            $this->_adminNames = [
                Yii::t('app', 'Menu items'),
                Yii::t('app', 'menu item'),
                Yii::t('app', 'menu items')
            ];
        }
        return $this->_adminNames;
    }

    /**
     * @inheritdoc
     * @return string
     */
	public function tableName()
	{
		return '{{menu}}';
	}

    /**
     * @inheritdoc
     * @return string
     */
	public function primaryKey()
	{
		return 'menu_id';
	}

    /**
     * @inheritdoc
     * @return array
     */
	public function defaultScope()
	{
		return array(
			'order' => '`order` ASC',
		);
	}

    /**
     * @return CActiveDataProvider
     */
	public function search()
	{
		$this->getDbCriteria()->mergeWith([
            'order' => 'parent_menu_id ASC, `order` ASC'
        ]);

		if (isset($_GET['Menu'])) {
			$this->attributes = $_GET['Menu'];
		}

		return new CActiveDataProvider($this);
	}

    /**
     * @param string $className
     * @return Menu
     */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @inheritdoc
     * @return bool
     */
	public function beforeValidate()
	{
        // deny self referencing
		if (!$this->isNewRecord && $this->parent_menu_id == $this->menu_id) {
			$this->addError('parent_menu_id', Yii::t('app', 'Menu item cannot be parent for itself'));
			return false;
		}
		return parent::beforeValidate();
	}

    /**
     * @inheritdoc
     * @return bool
     */
	public function beforeSave()
	{
		if ($this->home == 1) {
			$this->updateAll(array(
				'home' => 0,
			));
		}
		return parent::beforeSave();
	}

    /**
     * Defines validation rules
     * @return array
     */
	public function rules()
	{
		return array(
			array('url', 'unique'),
			array('anchour,url', 'required'),
			array('url', 'url', 'pattern' => '/[a-z0-9_-]+/is'),
			array('parent_menu_id,home,order', 'safe')
		);
	}

    /**
     * @return array
     */
	public function attributeLabels()
	{
		return array(
			'enabled' => Yii::t('app', 'Menu item enabled'),
			'home' => Yii::t('app', 'Main menu item'),
			'parent_menu_id' => Yii::t('app', 'parent menu item'),
			'anchour' => Yii::t('app', 'Anchor'),
			'url' => Yii::t('app', 'URL'),
			'order' => Yii::t('app', 'Sort order')
		);
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
			$subitems[] = $subitem->menu_id;
			$subitems = array_merge($subitems, $subitem->getAllSubitems());
		}

		return array_unique($subitems);
	}

    /**
     * @return array
     */
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
		return array_merge(
            array(
			    '0' => '-= Нет родительского меню =-'
            ),
            CHtml::listData($parentMenuItems, 'menu_id', 'anchour')
        );
	}

    /**
     * @return array
     */
	public function attributeWidgets()
	{
		return array(
			array('parent_menu_id', 'dropDown'),
			array('enabled', 'boolean'),
			array('home', 'boolean')
		);
	}

    /**
     * @return mixed|string
     */
	public function getFullAnchour()
	{
		if ($this->parent && $_anchour = $this->parent->getFullAnchour()) {
			return $_anchour . ' > ' . $this->anchour;
		}

        return $this->anchour;
	}

    /**
     * @return string
     */
	public function getUrl()
	{
		if ($this->home == 1) {
            return '/';
        }

		if ($this->parent) {
            return $this->parent->getUrl() . '/' . $this->url;
        }

		return '/' . $this->url;
	}

    /**
     * @return string
     */
	public function getFullUrl()
	{
		if ($this->home == 1) {
            return Yii::app()->request->getBaseUrl(true);
        }

		if ($this->parent && $_url = $this->parent->getFullUrl()) {
			return $_url . '/' . $this->url;
		}

        return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

    /**
     * @return $this
     */
    public function enabled()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'enabled=:enabled',
            'params' => array(
                ':enabled' => 1
            )
        ]);
        return $this;
    }

    /**
     * @return bool
     */
	public function getIsActive()
	{
		if (Yii::app()->request->getUrl() == '/' && $this->home == 1) {
            return true;
        }

		if ($this->getFullUrl() == Yii::app()->request->getBaseUrl(true) . Yii::app()->request->getUrl()) {
            return true;
        }

		return false;
	}

    /**
     * @return array
     */
	public function adminSearch()
	{
		return array(
			'columns' => array(
				array(
					'name' => 'anchour',
					'value' => function(Menu $model) {
                        return $model->getFullAnchour();
                    }
				),
				array(
					'name' => 'url',
                    'type' => 'raw',
                    'value' => function(Menu $model) {
                        return CHtml::link($model->getFullUrl(), $model->getFullUrl(), array("target" => "_blank"));
                    },
				),
				'order'
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
			'parent' => array(self::HAS_ONE, 'Menu', array('menu_id' => 'parent_menu_id')),
			'children' => array(self::HAS_MANY, 'Menu', array('parent_menu_id' => 'menu_id'))
		);
	}

    /**
     * @inheritdoc
     * @return array
     */
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