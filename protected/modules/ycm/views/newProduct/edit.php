<div class="row-fluid">
    <div class="span10">
        <?php
        /* @var $form TbActiveForm */
        /* @var $this NewProductController */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        $this->widget('chosen.EChosenWidget');
        echo $form->dropDownListRow($newProductModel, 'product_id', CHtml::listData(
                        Products::model()->findAll(), 'product_id', 'title'
                ), array(
            'empty' => Yii::t('catalog', '-= Select product =-'),
            'class' => 'span5 chzn-select'
        ));
        echo $form->textFieldRow($newProductModel, 'order');
        ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'buttons' => array(
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => Yii::t('YcmModule.ycm', 'Save'),
                        'htmlOptions' => array('name' => '_save')
                    ),
                ),
            ));
            ?>
        </div>
        <?php $this->endWidget() ?>
    </div>
</div>