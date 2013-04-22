<?php

/**
 * Description of LanguageController
 *
 * @author shults
 */
class LanguageController extends AdminController
{

    public function actionSet($language_id)
    {
        if (($language = Language::model()->findByPk($language_id)) === null)
            throw new CHttpException(404);
        Yii::app()->request->cookies[LanguageComponent::LANGUAGE_COOKIE_KEY] = new CHttpCookie(LanguageComponent::LANGUAGE_COOKIE_KEY, $language->code);
        if (($referer = Yii::app()->request->getUrlReferrer()) !== null)
            $this->redirect($referer);
        else
            $this->redirect($this->module->homeUrl);
    }

}
