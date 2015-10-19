<?php
/**
 * @property string $title Page title
 * @property string $text Page text
 * @property string $url Page url
 */
class Page extends CActiveRecord
{

    /**
     * @return array
     */
    public function getAdminNames()
    {
        return [
            Yii::t('app', 'Pages'),
            Yii::t('app', 'Page'),
            Yii::t('app', 'pages')
        ];
    }

	public function tableName()
	{
		return '{{pages}}';
	}

	public function primaryKey()
	{
		return 'page_id';
	}

    /**
     * @return CActiveDataProvider
     */
	public function search()
	{
        $data = isset($_GET['Page']) ? $_GET['Page'] : [];
		$criteria = $this->getDbCriteria();

		if (!empty($data )) {
			$this->attributes = $data;

			if (isset($data['meta_title'])) {
				$criteria->addSearchCondition('meta_title', $data['meta_title']);
			}
		}

		return new CActiveDataProvider($this);
	}

    /**
     * @param string $className
     * @return Page
     */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            $this->language_id = Yii::app()->lang->language_id;
        }

        return parent::beforeSave();
    }

    /**
     * Sets validation rules
     * @return array
     */
	public function rules()
	{
		return array(
			array('url', 'unique'),
			array('title,url', 'required'),
			array('url', 'url', 'pattern' => '/[a-z0-9-_\/]+/is'),
			array('language_id, enabled, text, meta_title, meta_keywords, meta_description, created_at, updated_at', 'safe')
		);
	}

    /**
     * @return array
     */
	public function attributeLabels()
	{
		return array(
			'enabled' => Yii::t('app', 'Page enabled'),
			'title' => Yii::t('app', 'Page name'),
			'url' => Yii::t('app', 'URL'),
			'text' => Yii::t('app', 'Text'),
			'meta_title' => Yii::t('app', 'Meta title'),
			'meta_keywords' => Yii::t('app', 'Meta keywords'),
			'meta_description' => Yii::t('app', 'Description meta')
		);
	}

    /**
     * @return array
     */
	public function getAllSubitems()
	{
		if (!$this->children) {
            return [];
        }

		$subitems = [];

		foreach ($this->children as $subitem) {
			$subitems[] = $subitem->page_id;
			$subitems = array_merge($subitems, $subitem->getAllSubitems());
		}

		return array_unique($subitems);
	}

    /**
     * Defined widgets for YCM module
     * @return array
     */
	public function attributeWidgets()
	{
		return array(
			array('text', 'tinymce'),
			array('enabled', 'boolean'),
			array('meta_keywords', 'textArea'),
			array('meta_description', 'textArea'),
		);
	}

    /**
     * @return String
     */
	public function getFullTitle()
	{
		return $this->title;
	}

    /**
     * @return string
     */
	public function getFullUrl()
	{
		return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

    /**
     * Defines admin YCM columns
     * @return array
     */
	public function adminSearch()
	{
		return array(
			'columns' => array(
				array(
					'name' => 'title',
					'value' => function(Page $model) {
                        return $model->getFullTitle();
                    }
				),
				'meta_title',
				array(
					'name' => 'url',
					'value' => function(Page $model) {
                        return CHtml::link($model->getFullUrl(), $model->getFullUrl(), [
                            'target' => '_blank'
                        ]);
                    },
					'type' => 'raw',
				)
			),
		);
	}

    /**
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

    /**
     * Defined default scope
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => '`language_id`=:language_id',
            'params' => array(
                ':language_id' => Yii::app()->lang->language_id
            ),
        );
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

}