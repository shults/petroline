<?php

class SiteController extends FrontController
{

    public function actionIndex()
    {
        $this->layout = '//layouts/index';
        $this->render('index');
    }

    /**
     * Contact page with
     */
    public function actionContact()
    {
        $this->render('contact');
    }

}