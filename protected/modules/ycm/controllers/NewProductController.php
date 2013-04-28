<?php

/**
 * Description of NewProductController
 *
 * @author shults
 */
class NewProductController extends AdminController
{

    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';
    const GRID_VIEW_WIDGET_ID = 'new-product-grid-view';

    public function actionIndex()
    {
        $this->breadcrumbs = array(
            Yii::t('catalog', 'Modules'),
            Yii::t('catalog', 'New products')
        );
        $newProductModel = new NewProduct();
        $this->render('index', array(
            'newProductModel' => $newProductModel
        ));
    }

    public function actionAdd()
    {
        $this->actionEdit();
    }

    public function actionEdit($product_id = null)
    {
        $actionId = strtolower(Yii::app()->controller->action->id);
        $this->breadcrumbs = array(
            Yii::t('catalog', 'New products') => array('newProduct/index')
        );
        if ($actionId === self::ACTION_ADD) {
            $this->breadcrumbs += array(
                Yii::t('catalog', 'Add product'),
            );
        } else if ($actionId === self::ACTION_EDIT) {
            $this->breadcrumbs += array(
                Yii::t('catalog', 'Edit product'),
            );
        }

        if (!$product_id) {
            $newProductModel = new NewProduct();
        } else {
            if (($newProductModel = NewProduct::model()->findByPk($product_id)) === null)
                throw new CHttpException(404);
        }

        if (isset($_POST['NewProduct'])) {
            $newProductModel->attributes = $_POST['NewProduct'];
            if ($newProductModel->validate()) {
                $newProductModel->save(false);
                $this->redirect(array('newProduct/index'));
            }
        }
        $this->render('edit', array(
            'newProductModel' => $newProductModel
        ));
    }

    public function actionDelete($product_id)
    {
        if (($newProductModel = NewProduct::model()->findByPk($product_id)) == null)
            throw new CHttpException(404);
        $newProductModel->delete();
        if (!isset($_GET['ajax']))
            $this->redirect('newProduct/index');
    }

    public function actionOrderUp($product_id)
    {
        if (($newProductModel = NewProduct::model()->findByPk($product_id)) === null)
            throw new CHttpException(404);
        $newProductModel->orderUp();
        if (!isset($_GET['ajax']))
            $this->redirect(array('newProduct/index'));
    }

    public function actionOrderDown($product_id)
    {
        if (($newProductModel = NewProduct::model()->findByPk($product_id)) === null)
            throw new CHttpException(404);
        $newProductModel->orderDown();
        if (!isset($_GET['ajax']))
            $this->redirect(array('newProduct/index'));
    }

}
