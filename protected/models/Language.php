<?php

/**
 * Description of Language
 *
 * @author shults
 */
class Language extends CActiveRecord
{

    /** @var array */
    protected static $_adminNames;

    /** @var array */
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

    /**
     * @return array
     */
    public function getAdminNames()
    {
        if (self::$_adminNames === null) {
            self::$_adminNames = array(
                Yii::t('app', 'Languages'),
                Yii::t('app', 'language'),
                Yii::t('app', 'languages')
            );
        }
        return self::$_adminNames;
    }

    /**
     * @return string
     */
    public function tableName()
    {
        return '{{languages}}';
    }

    /**
     * @return string
     */
    public function primaryKey()
    {
        return 'language_id';
    }

    /**
     * @return array
     */
    public function rules()
    {
        return array(
            array('code, title', 'required'),
            array('code', 'length', 'is' => 2),
            array('code', 'match', 'pattern' => '/[a-z]{2}/', 'message' => Yii::t('app', 'Field {attribute} can consist only letters')),
            array('default, enabled, deleted', 'safe'),
        );
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'enabled' => Yii::t('app', 'Enabled'),
            'default' => Yii::t('app', 'Default'),
            'title' => Yii::t('app', 'Title'),
            'code' => Yii::t('app', 'Code'),
        );
    }

    /**
     * @return array
     */
    public function attributeWidgets()
    {
        return array(
            array('enabled', 'boolean'),
            array('default', 'boolean')
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
     * @return array
     */
    public function adminSearch()
    {
        return array(
            'columns' => array(
                'title',
                'code',
                array(
                    'name' => 'default',
                    'value' => function(Language $model) {
                        return $model->default == 1 ? Yii::t("common", "Yes") : Yii::t("common", "No");
                    }
                ),
                array(
                    'name' => 'enabled',
                    'value' => function(Language $model) {
                        return $model->default == 1 ? Yii::t("common", "Yes") : Yii::t("common", "No");
                    }
                )
            )
        );
    }

    /**
     * Defines default scope
     * @return array
     */
    public function defaultScope()
    {
        return array(
            'condition' => 'deleted=:deleted',
            'params' => array(
                ':deleted' => 0
            )
        );
    }

    /**
     * @return bool
     */
    public function beforeSave()
    {
        // swirch off alla other langualges
        if ($this->default == 1) {
            Language::model()->updateAll(array('default' => 0));
        }
        return parent::beforeSave();
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

    /**
     * @return array
     */
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

    /**
     * By default finder
     * @return $this
     */
    public function byDefault()
    {
        $this->getDbCriteria()->mergeWith([
            'condition' => '`default`=:default',
            'params' => array(
                ':default' => 1
            )
        ]);
        return $this;
    }

}
