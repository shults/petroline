<?php

class OrderController extends AdminController
{

    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';

    public function actionIndex()
    {
        $this->breadcrumbs = array(
            Yii::t('order', 'Orders'),
        );

        $renderMethodName = isset($_GET['ajax']) ? 'renderPartial' : 'render';

        $this->$renderMethodName('index', array(
            'orderModel' => new Order
        ));
    }

    public function actionView($order_id)
    {
        $this->breadcrumbs = array(
            Yii::t('order', 'View order'),
        );
        Yii::import('bootstrap.widgets.TbGridView');
        if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));
        $productsDataProvider = new CActiveDataProvider('OrderProducts', array(
            'data' => $order->products,
        ));

        $this->render('view', array(
            'order' => $order,
            'productsDataProvider' => $productsDataProvider
        ));
    }

    public function actionAdd()
    {
        $this->actionEdit();
    }

    public function actionEdit($order_id = null)
    {
        switch (strtolower(Yii::app()->controller->action->id)) {
            case self::ACTION_ADD:
                $this->breadcrumbs = array(
                    Yii::t('order', 'Create order')
                );
                break;
            case self::ACTION_EDIT:
                $this->breadcrumbs = array(
                    Yii::t('order', 'Edit order')
                );
                break;
        }

        if ($order_id === null)
            $order = new Order;
        else if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));

        if ($order->status === Order::STATUS_EXECUTED) {
            Yii::app()->user->setFlash('warning', Yii::t('order', 'Order is alreadey excuted. You cannot change it.'));
            $this->redirect(array('order/view', 'order_id' => $order->order_id));
        }

        if (!$order->getIsNewRecord()) {
            $order->status = Order::STATUS_PERFOMED;
        }

        if (isset($_POST['Order'])) {
            $order->attributes = $_POST['Order'];
            if ($order->validate()) {
                $order->save(false);
                if (isset($_POST['_save_and_resume_addproducts']))
                    $this->redirect(array('order/edit', 'order_id' => $order->order_id));
                if (isset($_POST['_save']))
                    $this->redirect(array('order/index'));
            }
        }

        $this->render('edit', array(
            'order' => $order
        ));
    }

    public function actionDelete($order_id)
    {
        if (($order = Order::model()->findByPk($order_id)) == null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));
        $order->delete();
        if (!$_GET['ajax'])
            $this->redirect(array('order/index'));
    }

    public function actionAddProductToOrder($order_id, $product_id = null)
    {
        /* @var $order Order */
        /* @var $product Products */
        if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));

        if ($product_id !== null) {
            if (($product = Products::model()->findByPk($product_id)) === null)
                throw new CHttpException(404, Yii::t('order', 'Product not found'));
            if (!$order->addProductToOrder($product)) {
                Yii::app()->user->setFlash('warning', Yii::t('order', 'Product "{product}" already exists in this order', array('{product}' => $product->title)));
            }
            $this->redirect(array('order/edit', 'order_id' => $order->order_id));
        }

        $this->breadcrumbs = array(
            Yii::t('order', 'Edit order') => array('order/edit', 'order_id' => $order->order_id),
            Yii::t('order', 'Add product to order')
        );
        $productsModel = new Products;

        $renderMethod = (isset($_GET['ajax']) && isset($_GET['Products'])) ? 'renderPartial' : 'render';

        $this->$renderMethod('add_product_to_order', array(
            'order' => $order,
            'productsModel' => $productsModel
        ));
    }

    public function actionDeleteProductFromOrder($order_id, $product_id)
    {
        /* @var $order Order */
        if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('Such order does not exists'));
        if ($order->dropProductFromOrderByProductId($product_id)) {
            Yii::app()->user->setFlash('success', Yii::t('order', 'Product succesfully deleted from order'));
        } else {
            Yii::app()->user->setFlash('warning', Yii::t('order', 'Product had not been deleted from order'));
        }
        if (!isset($_GET['ajax']))
            $this->redirect(array('order/edit', 'order_id' => $order_id));
    }

    public function actionIncrementProductToOrder($order_id, $product_id)
    {
        /* @var $order Order */
        if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));
        $order->incrementProductById($product_id);
        if (!isset($_GET['ajax']))
            $this->redirect(array('order/edit', 'order_id' => $order_id));
    }

    public function actionDecrementProductFromOrder($order_id, $product_id)
    {
        /* @var $order Order */
        if (($order = Order::model()->findByPk($order_id)) === null)
            throw new CHttpException(404, Yii::t('order', 'Order not found'));
        $order->decrementProductById($product_id);
        if (!isset($_GET['ajax']))
            $this->redirect(array('order/edit', 'order_id' => $order_id));
    }

}
