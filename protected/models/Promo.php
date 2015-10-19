<?php

/**
 * Class Promo
 */
class Promo extends CActiveRecord
{

    /**
     * @param string $className
     * @return Promo
     */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * @inheritdoc
     * @return array
     */
	public function behaviors()
    {
		return array(
			'FileBehavior' => 'application.behaviors.FileBehavior'
		);
	}

    /**
     * @return string
     */
	public function tableName()
    {
		return '{{promo}}';
	}

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if ($this->_adminNames === null) {
            $this->_adminNames = array(
                Yii::t('app', 'Promo module'),
                Yii::t('app', 'promo object'),
                Yii::t('app', 'promo object'),
            );
        }
        return $this->_adminNames;
    }

    /**
     * @return CActiveDataProvider
     */
	public function search()
    {
		$criteria = $this->getDbCriteria();

        if (isset($_GET['Promo'])) {
			$this->attributes = $_GET['Promo'];

			if ($_GET['Promo']['title']) {
				$criteria->addSearchCondition('title', $_GET['Promo']['title']);
			}
		}

		return new CActiveDataProvider($this);
	}

    /**
     * @return array
     */
	public function adminSearch()
    {
		return array(
			'columns' => array(
				array(
					'name' => 'image',
                    'type' => 'raw',
                    'filter' => false,
					'value' => function(Promo $model) {
                        return CHtml::image($model->getFileUrl("image"), "", array("width" => 150));;
                    }
				),
				array(
					'name' => 'title',
				),
				array(
					'name' => 'url',
					'type' => 'raw',
					'filter' => false,
                    'value' => function(Promo $model) {
                        return CHtml::link($model->getPromoItemUrl(), $model->getPromoItemUrl(), array("target" => "_blank"));
                    },
				),
				array(
					'name' => 'order',
					'filter' => false
				)
			)
		);
	}

    /**
     * @return string
     */
	public function getPromoItemUrl()
    {
		if ($this->url == '#') {
            return '#';
        }
		return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

    /**
     * @inheritdoc
     * @return bool
     */
    public function beforeSave()
    {
        if (!$this->url) {
            $this->url = "#";
        }

        if ($this->isNewRecord) {
            $this->order = ++$this->maxOrder()->find()->order;
        }

		return parent::beforeSave();
	}

    /**
     * @inheritdoc
     * @return array
     */
	public function rules()
    {
		return array(
			array('order,enabled,url', 'safe'),
			array('title,image,description', 'required'),
			array('image', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
		);
	}

    /**
     * @inheritdoc
     */
	public function afterSave()
    {
		$this->imageSizeValidate('image');
		return parent::afterSave();
	}

    /**
     * @param $attribute
     * @param array $params
     */
	public function imageSizeValidate($attribute, $params = array('width' => 200, 'height' => 120))
    {
		$imagesize = getimagesize($this->getAbsoluteFileUrl($attribute));

        $width = $imagesize[0];
		$height = $imagesize[1];

		if ($width != $params['width'] || $height != $params['height']) {
			Yii::app()->user->setFlash(
                'error', Yii::t('carousel', 'Needed image size is {nwidth}x{nheight} px. Current uploaded file size is {width}x{height} px. Recomendation: Upload new file!', array(
                    '{width}' => $width,
                    '{height}' => $height,
                    '{nwidth}' => $params['width'],
                    '{nheight}' => $params['height']
                ))
            );
		}
	}

    /**
     * @return array
     */
	public function attributeLabels()
    {
		return array(
			'enabled' => Yii::t('app', 'Enabled'),
			'title' => Yii::t('app', 'Name'),
			'order' => Yii::t('app', 'Sort order'),
			'url' => Yii::t('app', 'URL'),
			'image' => Yii::t('app', 'Image'),
			'description' => Yii::t('app', 'Description')
		);
	}

    /**
     * @return array
     */
	public function attributeWidgets()
    {
		return array(
			array('enabled', 'boolean'),
			array('image', 'image'),
			array('description', 'wysiwyg')
		);
	}

    /**
     * @return $this
     */
    public function enabled()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 'enabled=:enabled',
            'params' => array(
                ':enabled' => 1
            )
        ));
        return $this;
    }

    /**
     * @return $this
     */
    public function maxOrder()
    {
        $this->getDbCriteria()->mergeWith(array(
            'select' => 'MAX(`order`) AS `order`',
        ));
        return $this;
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
            'order' => '`order` ASC',
        );
    }

}