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
$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => new CActiveDataProvider($productModel),
    'columns' => array(
        'title',
        'url',
        array(
            'name' => 'category_id',
            'value' => '$data->getCategoryTitle();',
        ),
        array(
            'name' => 'store_status',
            'value' => '$data->store_status == 1 ? "Есть" : "Нет"',
        ),
        array(
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}{delete}',
            'updateButtonUrl' => 'CHtml::normalizeUrl(array("product/edit", "product_id" => $data->product_id))',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("product/delete", "product_id" => $data->product_id))',
        ),
    )
));
?>