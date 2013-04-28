<?php
    //Create an instance of ColorBox
    $colorbox = $this->widget('application.extensions.colorpowered.JColorBox');

    //Call addInstance (chainable) from the widget generated.
    $colorbox->addInstance('.colorbox', array('maxHeight'=>'100%', 'maxWidth'=>'100%'));
    
    $imagestore = Yii::app()->params->imagestore;
    //$imagestore = Yii::app()->params->imagestore;
    $this->widget('bootstrap.widgets.TbGridView', array(
        'dataProvider' => $model->searchImages(),
        'id' => 'images',
        'summaryText' => '',
        'columns' => array(
            array(
                'name' => 'image',
                'type' => 'html',
                'htmlOptions'=>	array(
                    'width' => '200',
                    'height' => '100',
                ),
                'value'=> 'CHtml::link(CHtml::image("/'.$imagestore.'/".$data->folder."/".$data->filepath."", "Some text", array("width"=>100)), "/'.$imagestore.'/".$data->folder."/".$data->filepath."", array("class"=>"colorbox"))'
            ),
            array(
                'class'=>'CButtonColumn',
                'template' => '{delete}',
                'deleteButtonUrl' => 'CHtml::normalizeUrl(array("product/deleteImage", "image_id" => $data->image_id))',
            ),
        )
    ));

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => get_class($model) . '-id-form',
    'type' => 'horizontal',
    'inlineErrors' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
));

$this->endWidget();
/**
 * Drag & Drop upload files
 */
Yii::app()->clientScript->registerScript('xupload-gallery-images', "
    jQuery('#xupload_product_images').bind('fileuploadcompleted', function(e, data) {
        jQuery('#images').yiiGridView('update');
    });
    jQuery('#xupload_product_images').bind('fileuploaddestroyed', function(e, data) {
      jQuery('#images').yiiGridView('update');
    });
", CClientScript::POS_READY);

$product_id = intval(Yii::app()->request->getParam('product_id'));
$this->widget('xupload.XUpload', array(
    'url' => CHtml::normalizeUrl(array("product/upload", "product_id" => $product_id)),
    'model' => $upload_photos,
    'attribute' => 'file',
    'multiple' => true,
    'autoUpload' => true,
    'htmlOptions' => array(
        'class' => 'fileupload',
        'id' => 'xupload_product_images'
    ),
));
