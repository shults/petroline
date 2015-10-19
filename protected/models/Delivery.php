<?php

/**
 * Description of Delivery
 *
 * @author shults
 */
class Delivery extends CActiveRecord
{

    /**
     * @var
     */
    private static $_adminNames;

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{deliveries}}';
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function primaryKey()
    {
        return 'delivery_id';
    }

    /**
     * @see CActiveRecord
     * 
     * @param string $className
     * @return Delivery 
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(
                Yii::t('app', 'Deliveries'),
                Yii::t('app', 'delivery'),
                Yii::t('app', 'deliveries')
            );
        }
        return self::$_adminNames;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('title', 'required'),
            array('price', 'numerical', 'min' => 0, 'allowEmpty' => true),
            array('description, consider_price, show_order_comment, order_comment, deleted', 'safe'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'title' => Yii::t('app', 'Delivery title'),
            'consider_price' => Yii::t('app', 'Consider price'),
            'price' => Yii::t('app', 'Price'),
            'show_order_comment' => Yii::t('app', 'Show order comment'),
            'order_comment' => Yii::t('app', 'Order comment'),
            'description' => Yii::t('app', 'Delivery description')
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        return new CActiveDataProvider($this);
    }

    /**
     * Defines YCM search columnts
     * @return array
     */
    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title'
            )
        );
    }

    /**
     * Defines YCM widgets
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('description', 'tinymce'),
            array('consider_price', 'boolean'),
            array('show_order_comment', 'boolean')
        );
    }

    /**
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => 'deleted=:deleted AND language_id=:language_id',
            'params' => array(
                ':deleted' => 0,
                ':language_id' => Yii::app()->lang->language_id
            )
        );
    }

    /**
     * @return bool
     */
    public function beforeDelete()
    {
        $this->deleted = 1;
        $this->save(false);
        return false;
    }

}
