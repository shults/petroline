<?php

/**
 * Description of CatalogController
 *
 * @author shults
 */
class CatalogController extends FrontController
{

    public function actionProduct($product_id)
    {
        if (($product = Products::model()->findByPk($product_id)) === null)
            throw new CHttpException(404);

        //meta data
        $this->setPageTitle($product->getMetaTitle());
        $this->setMetaDescription($product->getMetaDescription());

        //breadcrumbs
        if ($product->category->parent) {
            $this->breadcrumbs = array(
                $product->category->parent->title => $product->category->parent->getFrontUrl(),
            );
        }
        $this->breadcrumbs = CMap::mergeArray($this->breadcrumbs, array(
                    $product->category->title => $product->category->getFrontUrl(),
                    $product->title
        ));

        Yii::app()->clientScript->registerScript('product-ajax', '
            jQuery(document).ready(function() {
                jQuery.getJSON("' . CHtml::normalizeUrl(array('catalog/getproduct', 'product_id' => $product->product_id)) . '", function(data){
                    jQuery("#product_description").html(data["description"]);
                });
            });
        ');

        $this->render('product', array(
            'product' => $product
        ));
    }

    public function actionCategory($category_id, $page = 1)
    {
        /* @var $category Categories */
        if (($category = Categories::model()->enabled()->findByPk($category_id)) === null)
            throw new CHttpException(404);
        //breadcrumbs
        if ($category->parent) {
            $this->breadcrumbs = array(
                $category->parent->title => $category->parent->getFrontUrl(),
            );
        }
        $this->breadcrumbs = CMap::mergeArray($this->breadcrumbs, array(
                    $category->title
        ));
        
        //meta data
        $this->setPageTitle($category->getMetaTitle());
        $this->setMetaDescription($category->getMetaDescription());

        if ($page > 1) {
            Yii::app()->clientScript->registerLinkTag('canonical', null, CHtml::normalizeUrl(array('catalog/category',
                        'category_id' => $category->category_id)));
        }

        // products search
        $categoriesInValues = array();
        $categoriesInValues[] = $category->category_id;
        if ($category->children) {
            /* @var $subCategory Categories */
            foreach ($category->children as $subCategory) {
                $categoriesInValues[] = $subCategory->category_id;
            }
        }
        $productsCriteria = new CDbCriteria;
        $productsCriteria->limit = Yii::app()->params['itemsPerPage'];
        $productsCriteria->offset = Yii::app()->params['itemsPerPage'] * ($page - 1);
        $productsCriteria->order = '`category_id` ASC, `order` ASC';
        $productsCriteria->scopes = array('enabled');
        $productsCriteria->addInCondition('category_id', $categoriesInValues);
        $products = Products::model()->findAll($productsCriteria);

        //pagination
        $totalProducts = Products::model()->count($productsCriteria);
        $pages = new CPagination($totalProducts);
        $pages->pageSize = $productsCriteria->limit;
        $pages->applyLimit($productsCriteria);
        $pages->route = 'catalog/category';
        $pages->params = array(
            'category_id' => $category_id
        );
        $pages->pageVar = 'page';

        $this->render('category', array(
            'category' => $category,
            'products' => $products,
            'pages' => $pages
        ));
    }

    public function actionSearch($q, $page = 1)
    {
        //set title
        $this->setPageTitle(Yii::t('common', 'Search results on query "{q}"', array('{q}' => $q)));

        //breadcrumbs
        $this->breadcrumbs = array($this->getPageTitle());

        //criteria
        $criteria = new CDbCriteria;
        $criteria->limit = Yii::app()->params['itemsPerPage'];
        $criteria->offset = Yii::app()->params['itemsPerPage'] * ($page - 1);
        $criteria->scopes = array('enabled');
        $keywords = preg_split('/[\s]+/i', $q);

        foreach ($keywords as $keyword) {
            $criteria->addSearchCondition('title', $keyword, true, 'OR');
            $criteria->addSearchCondition('description', $keyword, true, 'OR');
        }

        $products = Products::model()->findAll($criteria);

        //pagination
        $totalProducts = Products::model()->count($criteria);
        $pages = new CPagination($totalProducts);
        $pages->pageSize = $criteria->limit;
        $pages->applyLimit($criteria);
        $pages->route = 'catalog/search';
        $pages->pageVar = 'page';

        $this->render('search', array(
            'products' => $products,
            'pages' => $pages
        ));
    }

    public function actionGetProduct($product_id)
    {
        if (($product = Products::model()->resetScope()->findByPk($product_id)) === null)
            throw new CHttpException(404);
        header('Content-type: application/json');
        echo CJSON::encode($product->attributes);
        Yii::app()->end();
    }

}