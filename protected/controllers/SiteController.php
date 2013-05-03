<?php

class SiteController extends FrontController
{

    public function actionIndex()
    {
        $this->layout = '//layouts/index';

        $this->render('index', array(
            'newProducts' => NewProduct::model()->findAll(array(
                'limit' => 16,
                'offset' => 0
            ))
        ));
    }

    /**
     * Contact page with
     */
    public function actionContacts()
    {
        //$this->setPageTitle();
        //$this->setMetaDescription($description)
        //$this->setMetaKeywords($keywords)
        $this->render('page', array(
            'content' => Config::get('contacts'),
        ));
    }

    public function actionDelivery_payment()
    {
        $this->render('page', array(
            'content' => Config::get('delivery_payment'),
        ));
    }

    public function actionAbout_us()
    {
        $this->render('page', array(
            'content' => Config::get('about_us'),
        ));
    }

}