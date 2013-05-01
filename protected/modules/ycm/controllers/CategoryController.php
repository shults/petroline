<?php

/**
 * Description of CategoryController
 *
 * @author shults
 */
class CategoryController extends AdminController
{

    public function actionOrderUp($category_id)
    {
        /* @var $category Categories */
        if (($category = Categories::model()->findByPk($category_id)) === null)
            throw new CHttpException(404);
        $category->orderUp();
        if (!isset($_GET['ajax']))
            $this->redirect(array('model/list', 'name' => 'Categories'));
    }

    public function actionOrderDown($category_id)
    {
        /* @var $category Categories */
        if (($category = Categories::model()->findByPk($category_id)) === null)
            throw new CHttpException(404);
        $category->orderDown();
        if (!isset($_GET['ajax']))
            $this->redirect(array('model/list', 'name' => 'Categories'));
    }

}
