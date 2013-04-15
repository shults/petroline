<div class="control-group ">
    <label class="control-label"><?php echo Yii::t('order', 'Order products') ?></label>
    <div class="controls">
        <?php
        /* @var $this OrderController */
        /* @var $order OrderProducts */
        $widgetId = '_order_products_grid_view';
        $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => $widgetId,
            'dataProvider' => new CActiveDataProvider('OrderProducts', array(
                'data' => $order->products,
                    )),
            'columns' => array(
                array(
                    'header' => 'ID',
                    'name' => 'product_id'
                ),
                array(
                    'name' => 'item.title'
                ),
                array(
                    'name' => 'product_price',
                ),
                array(
                    'name' => 'number_of_products',
                ),
                array(
                    'header' => Yii::t('order', 'Subtotal price'),
                    'value' => 'sprintf("%0.2f", $data->product_price * $data->number_of_products)',
                ),
                array(
                    'class' => 'bootstrap.widgets.TbButtonColumn',
                    'template' => '{decrement}{increment}{delete}',
                    'deleteButtonUrl' => 'CHtml::normalizeUrl(array("order/deleteProductFromOrder", "order_id" => '
                                         . $order->order_id . ', "product_id" => $data->item->product_id))',
                    'buttons' => array(
                        'increment' => array(
                            'icon' => 'plus',
                            'url' => 'CHtml::normalizeUrl(array("order/incrementProductToOrder", "order_id" => '
                                     . $order->order_id . ', "product_id" => $data->item->product_id))',
                            'label' => Yii::t('order', 'Increment'),
                            'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . $widgetId . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . $widgetId . "').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                        ),
                        'decrement' => array(
                            'icon' => 'minus',
                            'url' => 'CHtml::normalizeUrl(array("order/decrementProductFromOrder", "order_id" => '
                                     . $order->order_id . ', "product_id" => $data->item->product_id))',
                            'label' => Yii::t('order', 'Decrement'),
                            'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . $widgetId . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . $widgetId . "').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                        )
                    )
                ),
            ),
            'enableSorting' => false,
        ));
        ?>
    </div>
</div>