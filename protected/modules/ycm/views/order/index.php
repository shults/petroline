<div class="btn-toolbar">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => '',
        'buttons' => array(
            array(
                'type' => 'primary',
                'label' => Yii::t('order', 'Create order'),
                'url' => array('order/add'),
            ),
        ),
    ));
    ?>
</div>
<?php
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CActiveDataProvider($orderModel),
    'columns' => array(
        'customer_full_name',
        'customer_phone',
        array(
            'name' => 'status',
            'value' => 'Yii::t("order", $data->status)',
        ),
        array(
            'name' => 'incoming_date',
            'value' => 'Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($data->incoming_date, \'yyyy-MM-dd HH:mm:ss\'))',
        ),
        array(
            'name' => 'total_price',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("order/edit", "order_id" => $data->order_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("order/delete", "order_id" => $data->order_id))',
            'viewButtonUrl' => 'CHtml::normalizeUrl(array("order/view", "order_id" => $data->order_id))'
        ),
    )
));
?>