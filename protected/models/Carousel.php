<?php

/**
 * Class Carousel
 */
class Carousel extends CActiveRecord
{

    const NEED_WIDTH = 676;
    const NEED_HEIGHT = 310;

    public function getAdminNames()
    {
        return [
            Yii::t('app', 'Carousel module'),
            Yii::t('app', 'slide'),
            Yii::t('app', 'slide'),
        ];
    }

    /**
     * @param string $className
     * @return Carousel
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
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
     * @return string
     */
    public function tableName()
    {
        return '{{carousel}}';
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
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
                    'value' => function(Carousel $model) {
                        return CHtml::image($model->getFileUrl("image"), "", array("width" => 150));
                    }
                ),
                array(
                    'name' => 'title',
                ),
                array(
                    'name' => 'url',
                    'type' => 'raw',
                    'filter' => false,
                    'value' => function(Carousel $model) {
                        return CHtml::link($model->getCarouselItemUrl(), $model->getCarouselItemUrl(), array("target" => "_blank"));
                    }
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
    public function getCarouselItemUrl()
    {
        return $this->url === '#' ? $this->url : Yii::app()->request->getBaseUrl(true) . '/' . $this->url;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (!$this->url) {
            $this->url = "#";
        }

        if ($this->getIsNewRecord()) {
            $this->order = ++$this->maxOrder()->find()->order;
            $this->language_id = Yii::app()->lang->language_id;
        }

        return parent::beforeSave();
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('order, enabled, url, language_id', 'safe'),
            array('title, image', 'required'),
            array('image', 'file', 'allowEmpty' => true, 'types' => 'jpg,jpeg,png,gif'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'enabled' => \Yii::t('app', 'Enabled'),
            'title' => \Yii::t('app', 'Name'),
            'order' => \Yii::t('app', 'Sort order'),
            'url' => \Yii::t('app', 'URL'),
            'image' => \Yii::t('app', 'Image <span style="color: #f00;">676x310</span>')
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('enabled', 'boolean'),
            array('image', 'image')
        );
    }

    /**
     * @return $this
     */
    public function maxOrder()
    {
        $this->getDbCriteria()->mergeWith([
            'select' => 'MAX(`order`) AS `order`',
        ]);
        return $this;
    }

    /**
     * @return $this
     */
    public function enabled()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'enabled=1'
        ]);
        return $this;
    }

    /**
     * @return $this
     */
    public function front()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => 'enabled=1',
            'limit' => 5,
            'offset' => 0
        ]);
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

    public function afterSave()
    {
        parent::afterSave();
        if ($this->image) {
            list($width, $height) = getimagesize($this->getFilePath('image'));

            if ($width != self::NEED_WIDTH || $height != self::NEED_HEIGHT) {
                Yii::app()->user->setFlash(
                    'error',
                    Yii::t(
                        'app',
                        'Needed image size is {nwidth}x{nheight} px. Current uploaded file size is {width}x{height} px. Recomendation: Upload new file!',
                        array(
                            '{nwidth}' => self::NEED_WIDTH,
                            '{nheight}' => self::NEED_HEIGHT,
                            '{width}' => $width,
                            '{height}' => $height
                        )
                    )
                );
            }
        }
    }
    
    /**
     * @return String url of image
     */
    public function getImageUrl()
    {
        return ImageModel::model()->resize($this->getFilePath('image'), self::NEED_WIDTH, self::NEED_HEIGHT);
    }

}