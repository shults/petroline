<?php

/**
 * Description of CartController
 *
 * @author shults
 */
class CartController extends FrontController
{

    public function actionView()
    {
        $this->breadcrumbs = array(
            Yii::t('breadcrumbs', 'Cart')
        );
        $order = new Order;
        if (isset($_POST['Order'])) {
            $order->attributes = $_POST['Order'];
            if ($order->validate() && count(Cart::get()->getProducts()) > 0) {
                $order->save();
                /* @var $product CartProduct */
                foreach (Cart::get()->getProducts() as $product) {
                    $orderProduct = new OrderProducts;
                    $orderProduct->order_id = $order->order_id;
                    $orderProduct->product_id = $product->product_id;
                    $orderProduct->product_price = $product->product->price;
                    $orderProduct->number_of_products = $product->count;
                    $orderProduct->save(false);
                }
                $order->save();
                Cart::get()->cleanCart();
                $this->redirect(array('cart/order_compleate'));
            }
        }
        $this->render('view', array(
            'products' => Cart::get()->getProducts(),
            'order' => $order
        ));
    }
    
    public function actionOrder_compleate()
    {
        $this->render('application.views.site.page', array(
            'content' => Config::get('order_compleate')
        ));
    }

    public function actionAdd($product_id)
    {
        if (($product = Products::model()->findByPk($product_id) ) === null)
            throw new CHttpException(404);
        Cart::get()->addProduct($product);
        $this->redirect(array('cart/view'));
    }

    public function actionIncrement($product_id)
    {
        Cart::get()->incrementProduct($product_id);
        if (!isset($_GET['ajax']))
            $this->redirect(array('cart/view'));
    }

    public function actionDecrement($product_id)
    {
        Cart::get()->decrementProduct($product_id);
        if (!isset($_GET['ajax']))
            $this->redirect(array('cart/view'));
    }

    public function actionRemove($product_id)
    {
        Cart::get()->removeProduct($product_id);
        if (!isset($_GET['ajax']))
            $this->redirect(array('cart/view'));
    }

}