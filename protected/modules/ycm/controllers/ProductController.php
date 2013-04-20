<?php

class ProductController extends AdminController
{

    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';

    public function actions()
    {
        return array(
            'upload' => array(
                'class' => 'application.modules.ycm.actions.UploadAction',
                'folder' => md5(Yii::app()->params->salt . Yii::app()->request->getParam('product_id')),
            ),
        );
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
            'model' => $product
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

    public function actionDeleteImage()
    {
        $image_id = Yii::app()->request->getParam('image_id');
        if ($image_id) {
            $image = ProductImages::model()->find('image_id=:image_id', array(':image_id' => $image_id));
            $image->delete();
            $imagestrore = Yii::app()->params->imagestore;
            $path = realpath(Yii::app()->getBasePath() . "/../$imagestrore/{$image->folder}") . "/";
            if (is_file($path . $image->filepath)) {
                unlink($path . $image->filepath);
            } else {
                throw new Exception('No such file - ' . $path . $image->filepath);
            }
        } else {
            throw new CHttpException(404);
        }
    }

    protected function getTabs($form, $model)
    {
        $product_id = $model->product_id;

        if ($product_id) {
            Yii::import("xupload.models.XUploadForm");
            $upload_photos = new XUploadForm;
            $gallery_tab = array(
                'label' => 'Галерея',
                'content' => $this->renderPartial('product_images', array(
                    'upload_photos' => $upload_photos,
                    'model' => $model,
                        ), true)
            );
        } else {
            $gallery_tab = '';
        }

        $tabs = array(
            array(
                'active' => true,
                'label' => 'Основное',
                'content' => $this->renderPartial('main_data', array(
                    'model' => $model,
                    'form' => $form,
                        ), true)
            ),
            $gallery_tab
        );
        return $tabs;
    }

}
