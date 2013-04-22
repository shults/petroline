<?php

/**
 * Description of LanguageComponent
 *
 * @author shults
 */
class LanguageComponent extends CComponent
{

    const LANGUAGE_COOKIE_KEY = 'language_code';

    /**
     * Language model
     *
     * @var Language 
     */
    private $_language;

    public function getTitle()
    {
        return $this->_language->title;
    }

    public function getCode()
    {
        return $this->_language->code;
    }

    public function getLanguage_id()
    {
        return $this->_language->language_id;
    }

    public function init()
    {
        $this->_language = $this->getLanguage();
    }

    private function getLanguage()
    {
        /* @var $language Language */
        if (Yii::app()->request->cookies[LanguageComponent::LANGUAGE_COOKIE_KEY] === null)
            return $this->getDefaultLanguage();
        if (($language = Language::model()->find('code=:code', array('code' => Yii::app()->request->cookies[self::LANGUAGE_COOKIE_KEY]->value))) === null)
            return $this->getDefaultLanguage();
        else
            return $language;
    }

    private function getDefaultLanguage()
    {
        return Language::model()->find('`default`=:default', array(':default' => 1));
    }

}