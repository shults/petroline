<?php

class Config extends CActiveRecord
{

    public $adminNames = array('Настроки', 'настройку', 'настройка');

    public function tableName()
    {
        return '{{config}}';
    }

    public function beforeDelete()
    {
        Yii::app()->user->setFlash('error', 'Удаление невозможно');
        return false;
    }

    public function beforeSave()
    {
        if (!$this->isNewRecord && $this->key) {
            unset($this->key);
        }
        return parent::beforeSave();
    }

    public function primaryKey()
    {
        return 'id';
    }

    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public static function get($key)
    {
        return self::model()->find('`key`=:key', array(':key' => $key))->value;
    }

    public function search()
    {
        $criteria = new CDbCriteria;
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria
        ));
    }

    public function rules()
    {
        return array(
            array('key,value,title', 'required'),
            array('key', 'match', 'pattern' => '/[a-z0-9_-]+/i')
        );
    }

    public function attributeLabels()
    {
        return array(
            'key' => 'Ключ',
            'title' => 'Название ключа',
            'value' => 'Значение'
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('value', $this->widget)
        );
    }

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
