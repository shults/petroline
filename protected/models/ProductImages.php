<?php

/**
 * Class ProductImages
 */
class ProductImages extends CActiveRecord
{

    public static $MIME_TYPES = array('image/jpeg', 'image/png', 'image/gif', 'image/jpg');
    public $image;

    /** @var array */
    protected static $_adminNames;

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(
                Yii::t('app', 'Products'),
                Yii::t('app', 'product'),
                Yii::t('app', 'products')
            );
        }
        return self::$_adminNames;
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{product_images}}';
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function primaryKey()
    {
        return 'image_id';
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        return new CActiveDataProvider($this);
    }

    /**
     * @param string $className
     * @return ProductImages
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('product_id, filepath', 'safe'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'image' => Yii::t('app', 'Image'),
        );
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return array(
            'FileBehavior' => 'application.behaviors.FileBehavior',
        );
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->deleteFile();
        parent::afterDelete();
    }

    /**
     * Deletes file from disk
     */
    private function deleteFile()
    {
        @unlink(realpath(Yii::getPathOfAlias('root')) . $this->getFileUrl('filepath'));
    }
    
    /**
     * This method resizes and return url path tu image
     * 
     * @param int $width
     * @param int $height
     * @return String url tu resized image
     */
    public function getImageUrl($width, $height)
    {
        return ImageModel::model()->resize($this->getFilePath('filepath'), $width, $height);
    }

}