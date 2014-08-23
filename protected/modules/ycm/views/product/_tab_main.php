<div class="row-fluid">
    <div class="span10">
        <?php
        /* @var $this ProductController */
        /* @var $form TbActiveForm */
        /* @var $productModel Products */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));

        $this->module->createWidget($form, $productModel, 'category_id');
        $this->module->createWidget($form, $productModel, 'status');
        $this->module->createWidget($form, $productModel, 'display_ajax');
        $this->module->createWidget($form, $productModel, 'title');
        $this->module->createWidget($form, $productModel, 'url');
        $this->module->createWidget($form, $productModel, 'price');
        $this->module->createWidget($form, $productModel, 'store_status');
        $this->module->createWidget($form, $productModel, 'description');
        $this->module->createWidget($form, $productModel, 'trade_price');
        $this->module->createWidget($form, $productModel, 'min_trade_order');
        $this->module->createWidget($form, $productModel, 'meta_title');
        $this->module->createWidget($form, $productModel, 'meta_keywords');
        $this->module->createWidget($form, $productModel, 'meta_description');

        $buttons = array(
            array(
                'buttonType' => 'submit',
                'type' => 'primary',
                'label' => Yii::t('YcmModule.ycm', 'Save'),
                'htmlOptions' => array('name' => '_save')
            ),
            array(
                'buttonType' => 'submit',
                'label' => Yii::t('YcmModule.ycm', 'Save and add another'),
                'htmlOptions' => array('name' => '_addanother')
            ),
            array(
                'buttonType' => 'submit',
                'label' => Yii::t('YcmModule.ycm', 'Save and continue editing'),
                'htmlOptions' => array('name' => '_continue')
            ),
        );
        if (!$productModel->isNewRecord) {
            array_push($buttons, array(
                'buttonType' => 'link',
                'type' => 'danger',
                'url' => '#',
                'label' => Yii::t('YcmModule.ycm', 'Delete'),
                'htmlOptions' => array(
                    'submit' => array(
                        'product/edit',
                        'product_id' => $productModel->product_id,
                    ),
                    'confirm' => Yii::t('YcmModule.ycm', 'Are you sure you want to delete this item?'),
                )
            ));
        }
        ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'buttons' => $buttons
            ));
            ?>
        </div>
        <?php $this->endWidget() ?>
    </div>
</div>