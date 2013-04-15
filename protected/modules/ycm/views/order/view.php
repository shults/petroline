<?php

/* @var $this OrderController */
/* @var $order Order */
/* @var $productsDataProvider CActiveDataProvider */

$this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $order,
    'attributes' => array(
        'order_id',
        'customer_full_name',
        'customer_phone',
        'customer_email',
        'delivery_address',
        array(
            'name' => 'status',
            'value' => Yii::t("order", $order->status),
        ),
        array(
            'name' => 'incoming_date',
            'value' => Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($order->incoming_date, 'yyyy-MM-dd HH:mm:ss')),
        ),
        'payment.title',
        array(
            'name' => 'delivery.title',
            'value' => $order->delivery->title . " (" . (int) $order->delivery->price . " " . Yii::t("common", "UAH") . ")"
        ),
        array(
            'name' => Yii::t('catalog', 'Products'),
            'type' => 'raw',
            'value' => $this->widget('bootstrap.widgets.TbGridView', array(
                'type' => TbGridView::TYPE_BORDERED,
                'dataProvider' => $productsDataProvider,
                'enableSorting' => false,
                'columns' => array(
                    'item.title',
                    'product_price',
                    'number_of_products',
                    array(
                        'name' => 'total',
                        'value' => 'sprintf("%0.2f", $data->number_of_products * $data->product_price)'
                    )
                )
                        ), true)
        ),
        'total_price',
    )
));