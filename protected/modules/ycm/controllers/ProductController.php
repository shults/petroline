<?php

class ProductController extends AdminController
{

    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';
    const ADMIN_WIDGET_GRID_VIEW_ID = 'products-grid-view';

    public function actionUpload($product_id)
    {
        $productImagesModel = new ProductImages;
        if (!$imageFile = CUploadedFile::getInstance($productImagesModel, 'filepath'))
            throw new CHttpException(404);
        $productImagesModel->filepath = $newFileName = sha1(microtime() . uniqid()) . '.' . $imageFile->getExtensionName();
        $productImagesModel->product_id = $product_id;
        if (!$productImagesModel->save(false))
            throw new CException("Error write into db");
        $imageFileName = realpath(Yii::getPathOfAlias('root')) . $productImagesModel->getFileUrl('filepath');
        if (!$imageFile->saveAs($imageFileName))
            throw new CException("File does not saved");
    }

    public function actionIndex()
    {
        $this->breadcrumbs = array(
            Yii::t('products', 'Products'),
        );

        $renderMethodName = isset($_GET['ajax']) ? 'renderPartial' : 'render';

        $this->$renderMethodName('index', array(
            'productModel' => new Products()
        ));
    }

    public function actionAdd()
    {
        $this->actionEdit();
    }

    public function actionEdit($product_id = null)
    {
        Yii::import("xupload.models.XUploadForm");
        switch (strtolower(Yii::app()->controller->action->id)) {
            case self::ACTION_ADD:
                $this->breadcrumbs = array(
                    Yii::t('products', 'Create product')
                );
                break;
            case self::ACTION_EDIT:
                $this->breadcrumbs = array(
                    Yii::t('products', 'Edit product')
                );
                break;
        }

        if ($product_id === null)
            $product = new Products;
        else if (($product = Products::model()->findByPk($product_id)) === null)
            throw new CHttpException(404, Yii::t('products', 'Products not found'));

        if (isset($_POST['Products'])) {
            $product->attributes = $_POST['Products'];
            if ($product->validate()) {
                $product->save(false);
                if (isset($_POST['_save_and_resume_addproducts']))
                    $this->redirect(array('product/edit', 'product_id' => $product->product_id));
                if (isset($_POST['_save']))
                    $this->redirect(array('product/index'));
            }
        }

        $this->render('edit', array(
            'tabs' => $this->getTabs($product)
        ));
    }

    public function actionDelete($product_id)
    {
        if (($product = Products::model()->findByPk($product_id)) == null)
            throw new CHttpException(404, Yii::t('products', 'Products not found'));
        $product->delete();
        if (!$_GET['ajax'])
            $this->redirect(array('product/index'));
    }

    public function actionDeleteImage($image_id)
    {
        if (($productImage = ProductImages::model()->findByPk($image_id)) === null)
            throw new CHttpException(404);
        $productImage->delete();
    }

    protected function getTabs(Products $productModel)
    {
        return array(
            array(
                'active' => true,
                'label' => Yii::t('catalog', 'Main data'),
                'content' => $this->renderPartial('_tab_main', array(
                    'productModel' => $productModel
                        ), true)
            ),
            array(
                'visible' => !$productModel->getIsNewRecord(),
                'label' => Yii::t('catalog', 'Images'),
                'content' => $this->renderPartial('_tab_images', array(
                    'productModel' => $productModel
                        ), true)
            )
        );
    }

    public function actionOrderUp($product_id)
    {
        /* @var $product Products */
        if (($product = Products::model()->findByPk($product_id)) === null)
            throw new CHttpException(404);
        $product->orderUp();
        if (!isset($_GET['ajax']))
            $this->redirect(array('product/index'));
    }

    public function actionOrderDown($product_id)
    {
        /* @var $product Products */
        if (($product = Products::model()->findByPk($product_id)) === null)
            throw new CHttpException(404);
        $product->orderDown();
        if (!isset($_GET['ajax']))
            $this->redirect(array('product/index'));
    }

    /**
     * Change global price of products.
     * Example: rise up price for all products in 15% $rate = 0.15
     *
     * @param $rate float
     */
    public function actionGlobalPriceChange()
    {
        $globalPriceChangeForm = new GlobalPriceChangeForm;

        if (isset($_POST['GlobalPriceChangeForm'])) {
            $globalPriceChangeForm->setAttributes($_POST['GlobalPriceChangeForm']);

            if ($globalPriceChangeForm->validate()) {
                $globalPriceChangeForm->changePrices();
                $this->redirect(array('order/index'));
            }
        }

        $this->render('global_price_change', array('globalPriceChangeForm' => $globalPriceChangeForm));
    }

}
