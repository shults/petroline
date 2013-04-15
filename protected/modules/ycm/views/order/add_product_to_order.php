<?php

/* @var $this OrderController */
/* @var $productsModel Products */
/* @var $order Order */
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $productsModel->addProductSearch(),
    'columns' => array(
        'title',
        array(
            'name' => 'category_id',
            'filter' => CHtml::activeDropDownList($productsModel, 'category_id', 
                        CMap::mergeArray(
                                array(
                                    '' => Yii::t('order', '-= Select category =-')
                                ), CHtml::listData(Categories::model()->findAll(), 'category_id', 'title'))
                        ),
            'value' => '$data->category->title'
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{add}',
            'buttons' => array(
                'add' => array(
                    'icon' => 'plus',
                    'url' => 'CHtml::normalizeUrl(array("order/addProductToOrder", "order_id" => ' . $order->order_id . ', "product_id" => $data->product_id));'
                )
            )
        ),
    ),
    'enableSorting' => false,
    'filter' => $productsModel
));
