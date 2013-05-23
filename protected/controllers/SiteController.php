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
        $this->setPageTitle(Config::get('contacts_title'));
        $this->breadcrumbs = array(
            $this->getPageTitle()
        );
        $this->render('page', array(
            'content' => Config::get('contacts'),
        ));
    }

    public function actionDelivery_payment()
    {
        $this->setPageTitle(Config::get('delivery_payment_title'));
        $this->breadcrumbs = array(
            $this->getPageTitle()
        );
        $this->render('page', array(
            'content' => Config::get('delivery_payment'),
        ));
    }

    public function actionAbout_us()
    {
        $this->setPageTitle(Config::get('about_us_title'));
        $this->breadcrumbs = array(
            $this->getPageTitle()
        );
        $this->render('page', array(
            'content' => Config::get('about_us'),
        ));
    }
    
    public function actionSitemap()
    {
        header('Content-type: application/xml');
        $this->renderPartial('sitemap', array(
            'products' => Products::model()->getAllProducts(),
            'categories' => Categories::model()->getAllCategories(),
            'staticPages' => array(
                '/contacts',
                '/uk/contacts',
                '/about_us',
                '/uk/about_us',
                '/delivery_payment',
                '/uk/delivery_payment'
            )
        ));
    }

}