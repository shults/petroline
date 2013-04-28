<?php

class SiteController extends FrontController
{

    public function actionIndex()
    {
        $this->layout = '//layouts/index';
        //$categories = Categories::model()->findAll();
        $this->render('index', array(
            //'categories' => $categories
        ));
    }

    /**
     * Contact page with
     */
    public function actionContacts()
    {
        
    }
    
    public function actionDelivery_payment()
    {
        
    }
    
    public function actionAbout_us()
    {
        
    }

}