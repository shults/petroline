<?php

class Carousel extends CActiveRecord
{

    const NEED_WIDTH = 676;
    const NEED_HEIGHT = 310;

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior'
        );
    }

    public $adminNames = array('Модуль карусель', 'слайд', 'слайд');

    public function tableName()
    {
        return '{{carousel}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public function adminSearch()
    {
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
                    'value' => 'CHtml::link($data->getCarouselItemUrl(), $data->getCarouselItemUrl(), array("target" => "_blank"));',
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

    public function getCarouselItemUrl()
    {
        if ($this->url == '#')
            return '#';
        return Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
    }

    public function beforeSave()
    {
        if (!$this->url)
            $this->url = "#";
        if ($this->isNewRecord) {
            $this->order = ++$this->maxOrder()->find()->order;
            $this->language_id = Yii::app()->lang->language_id;
        }
        return parent::beforeSave();
    }

    public function rules()
    {
        return array(
            array('order, enabled, url, language_id', 'safe'),
            array('title,image', 'required'),
            array('image', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'enabled' => 'Включен',
            'title' => 'Название',
            'order' => 'Порядок отображения',
            'url' => 'URL',
            'image' => 'Изображение' . ' <span style="color: #f00;">676x310</span>'
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('enabled', 'boolean'),
            array('image', 'image')
        );
    }

    public function scopes()
    {
        return array(
            'enabled' => array(
                'condition' => 'enabled=1'
            ),
            'maxOrder' => array(
                'select' => 'MAX(`order`) AS `order`',
            ),
            'front' => array(
                'condition' => 'enabled=1',
                'limit' => 5,
                'offset' => 0
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

    public function afterSave()
    {
        parent::afterSave();
        if ($this->image) {
            list($width, $height) = getimagesize($this->getFilePath('image'));
            if ($width != self::NEED_WIDTH || $height != self::NEED_HEIGHT) {
                Yii::app()->user->setFlash(
                        'error', 
                        Yii::t(
                                'carousel', 
                                'Needed image size is {nwidth}x{nheight} px. Current uploaded file size is {width}x{height} px. Recomendation: Upload new file!', 
                                array(
                                    '{nwidth}' => self::NEED_WIDTH,
                                    '{nheight}' => self::NEED_HEIGHT,
                                    '{width}' => $width,
                                '{height}' => $height
                )));
            }
        }
    }
    
    
    /**
     * 
     * @return String url of image
     */
    public function getImageUrl()
    {
        return ImageModel::model()->resize($this->getFilePath('image'), self::NEED_WIDTH, self::NEED_HEIGHT);
    }

}