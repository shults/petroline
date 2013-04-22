<?php

class Promo extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function behaviors() {
		return array(
			'FileBehavior' => 'application.behaviors.FileBehavior'
		);
	}

	public $adminNames = array('Модуль промо', 'промо объект', 'промо объект');

	public function tableName() {
		return '{{promo}}';
	}

	public function primaryKey() {
		return 'id';
	}

	public function search() {
		$criteria = new CDbCriteria;
		if ($_GET['Promo']) {
			$this->attributes = $_GET['Promo'];
			if ($_GET['Promo']['title']) {
				$criteria->addSearchCondition('title', $_GET['Promo']['title']);
			}
		}
		return new CActiveDataProvider($this, array(
					'criteria' => $criteria,
				));
	}

	public function adminSearch() {
		return array(
			'columns' => array(
				array(
					'name' => 'image',
					'value' => 'CHtml::image($data->getFileUrl("image"), "", array("width" => 150));',
					'type' => 'raw',
					'filter' => false
				),
				array(
					'name' => 'title',
				),
				array(
					'name' => 'url',
					'value' => 'CHtml::link($data->getPromoItemUrl(), $data->getPromoItemUrl(), array("target" => "_blank"));',
					'type' => 'raw',
					'filter' => false
				),
				array(
					'name' => 'order',
					'filter' => false
				)
			)
		);
	}

	public function getPromoItemUrl() {
		if ($this->url == '#')
			return '#';
		return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
	}

	public function beforeSave() {
		if (!$this->url)
			$this->url = "#";
		if ($this->isNewRecord)
			$this->order = ++$this->maxOrder()->find()->order;
		return parent::beforeSave();
	}

	public function rules() {
		return array(
			array('order,enabled,url', 'safe'),
			array('title,image,description', 'required'),
			array('image', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
		);
	}

	public function afterSave() {
		$this->imageSizeValidate('image');
		return parent::afterSave();
	}

	public function imageSizeValidate($attribute, $params = array('width' => 200, 'height' => 120)) {
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
					)));
		}
	}

	public function attributeLabels() {
		return array(
			'enabled' => 'Включен',
			'title' => 'Название',
			'order' => 'Порядок отображения',
			'url' => 'URL',
			'image' => 'Изображение',
			'description' => 'Текст'
		);
	}

	public function attributeWidgets() {
		return array(
			array('enabled', 'boolean'),
			array('image', 'image'),
			array('description', 'wysiwyg')
		);
	}

	public function scopes() {
		return array(
			'enabled' => array(
				'condition' => 'enabled=1'
			),
			'maxOrder' => array(
				'select' => 'MAX(`order`) AS `order`',
			)
		);
	}

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