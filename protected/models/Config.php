<?php

/**
 * Class Config
 */
class Config extends CActiveRecord
{

    /**
     * @return array
     */
    public function getAdminNames()
    {
        return [
            Yii::t('app', 'Configs'),
            Yii::t('app', 'Config item'),
            Yii::t('app', 'config intem')
        ];
    }

    /**
     * @inheritdoc
     * @return string
     */
    public function tableName()
    {
        return '{{config}}';
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        Yii::app()->user->setFlash('error', Yii::t('app', 'Unable to delete'));
        return false;
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        if (!$this->isNewRecord && $this->key) {
            unset($this->key);
        }
        return parent::beforeSave();
    }

    /**
     * @param string $className
     * @return Config
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param $key
     * @return string|mixed
     */
    public static function get($key)
    {
        return self::model()->find('`key`=:key', array(':key' => $key))->value;
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        return new CActiveDataProvider($this);
    }

    /**
     * Defines validate rules
     * @return array
     */
    public function rules()
    {
        return array(
            array('key,value,title', 'required'),
            array('key', 'match', 'pattern' => '/[a-z0-9_-]+/i')
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'key' => Yii::t('app', 'Key'),
            'title' => Yii::t('app', 'Key name'),
            'value' => Yii::t('app', 'value')
        );
    }

    /**
     * Defines YCM widgets
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('value', $this->widget)
        );
    }

    /**
     * @return array
     */
    public function adminSearch()
    {
        return array(
            'columns' => array(
                array(
                    'name' => 'title',
                    'filter' => false
                ),
                array(
                    'name' => 'value',
                    'filter' => false
                )
            ),
        );
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
        );
    }

}
