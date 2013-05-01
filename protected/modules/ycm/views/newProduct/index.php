<div class="btn-toolbar">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => '',
        'buttons' => array(
            array(
                'type' => 'primary',
                'label' => Yii::t('catalog', 'Add product'),
                'url' => array('newProduct/add'),
            ),
        ),
    ));
    ?>
</div>
<?php
/* @var $this NewProductController */
/* @var $newProductModel NewProduct */
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $newProductModel->search(),
    'enableSorting' => false,
    'id' => NewProductController::GRID_VIEW_WIDGET_ID,
    'columns' => array(
        'item.title',
        array(
            //'name' => 'item.category.title',
            'value' => '$data->item->category->getFullCategoryTitle();',
            'header' => Yii::t('catalog', 'Category')
        ),
        'order',
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{up} {down} {update} {delete}',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("newProduct/edit", "product_id" => $data->product_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("newProduct/delete", "product_id" => $data->product_id))',
            'buttons' => array(
                'up' => array(
                    'icon' => 'icon-arrow-up',
                    'label' => 'Up',
                    'url' => 'CHtml::normalizeUrl(array("newProduct/orderUp", "product_id" => $data->product_id));',
                    'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . NewProductController::GRID_VIEW_WIDGET_ID . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . NewProductController::GRID_VIEW_WIDGET_ID . "').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                ),
                'down' => array(
                    'icon' => 'icon-arrow-down',
                    'label' => 'Down',
                    'url' => 'CHtml::normalizeUrl(array("newProduct/orderDown", "product_id" => $data->product_id));',
                    'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . NewProductController::GRID_VIEW_WIDGET_ID . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . NewProductController::GRID_VIEW_WIDGET_ID . "').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                ),
            ),
            'htmlOptions' => array(
                'style' => 'width: 100px; text-align: right;'
            )
        )
    )
));