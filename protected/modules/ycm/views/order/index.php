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
/* @var $orderModel Order */
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $orderModel->search(),
    'filter' => $orderModel,
    'columns' => array(
        'customer_full_name',
        'customer_phone',
        array(
            'name' => 'status',
            'value' => 'Yii::t("order", $data->status)',
            'filter' => $orderModel->getStatusChoises(),
        ),
        array(
            'name' => 'incoming_date',
            'value' => 'Yii::app()->dateFormatter->formatDateTime(CDateTimeParser::parse($data->incoming_date, \'yyyy-MM-dd HH:mm:ss\'))',
            'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'language' => 'ru',
                'model' => $orderModel,
                'attribute' => 'incoming_date',
                //'name' => 'Order[incoming_date]',
                'options' => array(
                    'showAnim' => 'fold',
                    'dateFormat' => 'dd.mm.yy',
                ),
                    ), true)
        ),
        array(
            'name' => 'total_price',
            'filter' => false,
            'value' => '$data->total_price . " " . Yii::t("common", "UAH");'
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("order/edit", "order_id" => $data->order_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("order/delete", "order_id" => $data->order_id))',
            'viewButtonUrl' => 'CHtml::normalizeUrl(array("order/view", "order_id" => $data->order_id))'
        ),
    )
));

Yii::app()->clientScript->registerScript('datepicker-live', "
    $(function() { 
        $.datepicker.setDefaults({'showAnim':'fold','dateFormat':'dd.mm.yy'});
        $('#Order_incoming_date').live('click', function () {
            $(this).datepicker(jQuery.extend({showMonthAfterYear:false},jQuery.datepicker.regional['ru'])).datepicker('show');
        });
    });    
");