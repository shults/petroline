<?php

/* @var $this AdminController */
/* @var $productModel Products */
/* @var $colorbox JColorBox */
$colorbox = $this->widget('ext.colorpowered.JColorBox');
Yii::app()->clientScript->registerScript("colorbox-images", "
    $('a.colorbox').live('click', function() {
        $.colorbox({href:$(this).attr('href'), open:true});
        return false;
    });
");

$this->widget('ext.dropzone.EDropzone', array(
    'model' => ProductImages::model(),
    'attribute' => 'filepath',
    'url' => CHtml::normalizeUrl(array('product/upload', 'product_id' => $productModel->product_id)),
    'mimeTypes' => ProductImages::$MIME_TYPES,
    'onSuccess' => "jQuery('#images').yiiGridView('update');",
));

$this->widget('bootstrap.widgets.TbGridView', array(
    'dataProvider' => $productModel->searchImages(),
    'id' => 'images',
    'summaryText' => '',
    'columns' => array(
        array(
            'name' => 'image',
            'type' => 'html',
            'htmlOptions' => array(
                'width' => '100',
                'height' => '100',
            ),
            'value' => 'CHtml::link(
                            CHtml::image(ImageModel::model()->preview($data->getFilePath("filepath"))),
                            ImageModel::model()->big($data->getFilePath("filepath")),
                            array(
                                "class"=>"colorbox"
                                )
                            );'
        ),
        array(
            'class' => 'CButtonColumn',
            'template' => '{delete}',
            'deleteButtonUrl' => 'CHtml::normalizeUrl(array("product/deleteImage", "image_id" => $data->image_id))',
        ),
    )
));
