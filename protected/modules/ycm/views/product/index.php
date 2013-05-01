<?php /* header('Content-type: text/plain; charset=utf-8;');
  print_r(CHtml::listData(Categories::model()->findAll(), 'category_id', 'title', 'parent.title'));
  Yii::app()->end() */ ?>
<div class="btn-toolbar">
    <?php
    $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type' => '',
        'buttons' => array(
            array(
                'type' => 'primary',
                'label' => Yii::t('products', 'Create product'),
                'url' => array('product/add'),
            ),
        ),
    ));
    ?>
</div>
<?php
/* @var $productModel Products */
$this->widget('bootstrap.widgets.TbGridView', array(
    'id' => ProductController::ADMIN_WIDGET_GRID_VIEW_ID,
    'dataProvider' => $productModel->search(),
    'filter' => $productModel,
    'columns' => array(
        'product_id',
        'title',
        array(
            'name' => 'category_id',
            'value' => '$data->getFullCategoryTitle();',
            'filter' => Categories::model()->listCategoriesData(),
        ),
        array(
            'name' => 'order',
            'filter' => false,
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{up}{down}{update}{delete}',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("product/edit", "product_id" => $data->product_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("product/delete", "product_id" => $data->product_id))',
            'buttons' => array(
                'up' => array(
                    'icon' => 'icon-arrow-up',
                    'label' => 'Up',
                    'url' => 'CHtml::normalizeUrl(array("product/orderUp", "product_id" => $data->product_id))',
                    'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . ProductController::ADMIN_WIDGET_GRID_VIEW_ID . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . ProductController::ADMIN_WIDGET_GRID_VIEW_ID . "').yiiGridView('update');
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
                    'url' => 'CHtml::normalizeUrl(array("product/orderDown", "product_id" => $data->product_id))',
                    'click' => "js: function() {
                                var th = this,
                                    afterDelete = function(){};
                                jQuery('#" . ProductController::ADMIN_WIDGET_GRID_VIEW_ID . "').yiiGridView('update', {
                                    type: 'POST',
                                    url: jQuery(this).attr('href'),
                                    success: function(data) {
                                        jQuery('#" . ProductController::ADMIN_WIDGET_GRID_VIEW_ID . "').yiiGridView('update');
                                        afterDelete(th, true, data);
                                    },
                                    error: function(XHR) {
                                        return afterDelete(th, false, XHR);
                                    }
                                });
                                return false;
                            }"
                )
            ),
            'htmlOptions' => array(
                'width' => '70'
            )
        ),
    )
));
?>