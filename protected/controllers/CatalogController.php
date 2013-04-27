<?php

/**
 * Description of CatalogController
 *
 * @author shults
 */
class CatalogController extends FrontController
{

    public function actionProduct()
    {
        
    }

    public function actionCategory($category_id)
    {
        $this->render('category');
    }

    public function actionSearch($q)
    {
        
    }

}