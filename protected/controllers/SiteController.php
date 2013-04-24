<?php

class SiteController extends FrontController
{

    public function actionIndex()
    {
        $this->render('index');
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Contact page with
     */
    public function actionContact()
    {
        $this->setPageTitle(Config::get('contact_meta_title'));
        $this->setMetaDescription(Config::get('contact_meta_description'));
        $this->setMetaKeywords(Config::get('contact_meta_keywords'));
        $message = new Message;
        if ($_POST['Message']) {
            $message->attributes = $_POST['Message'];
            if ($message->validate()) {
                $message->save(false);
                Yii::app()->user->setFlash('success', Config::get('msg_send_success'));
                $this->redirect(array('site/contact'));
            }
        }

        $this->render('contact', array(
            'message' => $message
        ));
    }

}