<?php

/**
 * Description of Language
 *
 * @author shults
 */
class Language extends CActiveRecord
{

    protected static $_adminNames;
    protected static $_menuItems;

    /**
     * @see CActiveRecord::model()
     * 
     * @param String $className
     * @return Language
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(self::t('Languages'), self::t('language'), self::t('languages'));
        }
        return self::$_adminNames;
    }

    public function tableName()
    {
        return '{{languages}}';
    }

    public function primaryKey()
    {
        return 'language_id';
    }

    public function rules()
    {
        return array(
            array('code, title', 'required'),
            array('code', 'length', 'is' => 2),
            array('code', 'match', 'pattern' => '/[a-z]{2}/', 'message' => Yii::t('language', 'Field {attribute} can consist only letters')),
            array('default, enabled, deleted', 'safe'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'enabled' => self::t('Enabled'),
            'default' => self::t('Default'),
            'title' => self::t('Title'),
            'code' => self::t('Code'),
        );
    }

    public function attributeWidgets()
    {
        return array(
            array('enabled', 'boolean'),
            array('default', 'boolean')
        );
    }

    public function search()
    {
        return new CActiveDataProvider($this);
    }

    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title',
                'code',
                array(
                    'name' => 'default',
                    'value' => '$data->default == 1 ? Yii::t("common", "Yes") : Yii::t("common", "No");'
                ),
                array(
                    'name' => 'enabled',
                    'value' => '$data->enabled == 1 ? Yii::t("common", "Yes") : Yii::t("common", "No");'
                )
            )
        );
    }

    public function defaultScope()
    {
        return array(
            'condition' => 'deleted=:deleted',
            'params' => array(
                ':deleted' => 0
            )
        );
    }

    public function beforeSave()
    {
        if ($this->default == 1) {
            Language::model()->updateAll(array('default' => 0));
        }
        return parent::beforeSave();
    }

    public static function t($message, $params = null, $source = null, $language = null)
    {
        return Yii::t('language', $message, $params, $source, $language);
    }

    public static function getMenuItems()
    {
        if (self::$_menuItems === null) {
            $menuItems = array();
            foreach (self::model()->findAll() as $language) {
                $menuItems[] = array(
                    'label' => $language->title,
                    'url' => array('language/set', 'language_id' => $language->language_id)
                );
            }
            self::$_menuItems = $menuItems;
        }
        return self::$_menuItems;
    }

}
