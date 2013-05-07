<?php

/**
 * Description of FrontLanguageComponent
 *
 * @author shults
 */
class FrontLanguageComponent extends CComponent implements ILanguageComponent
{

    /**
     * Current language model 
     *
     * @var Language 
     */
    private $_language;
    
    private $code;
    
    public function getLang()
    {
        return $this->_language;
    }

    public function getCode()
    {
        return $this->_language->code;
    }

    public function getLanguage_id()
    {
        return $this->_language->language_id;
    }

    public function getTitle()
    {
        return $this->_language->title;
    }

    public function init()
    {
        $defaultLanguage = Language::model()->default()->find();
        if (isset($_GET['language'])) {
            if (($language = Language::model()->find('code=:code', array(':code' => $_GET['language']))) === null) {
                //echo($_GET['language']);
                //die;
                //throw new CHttpException(404, Yii::t('common', 'Page not found'));
            }
            if ($language->code === $defaultLanguage->code) {
                $redirectUrl = substr(Yii::app()->request->getPathInfo(), 2);
                $redirectUrl = (!$redirectUrl) ? '/' : $redirectUrl;
                Yii::app()->request->redirect($redirectUrl);
            }
        } else {
            $language = $defaultLanguage;
        }
        $this->_language = $language;
        Yii::app()->setLanguage($language->code);
    }

    public function getLangPrefix()
    {
        if ($this->_language->default) {
            return null;
        }
        return $this->_language->code;
    }

}
