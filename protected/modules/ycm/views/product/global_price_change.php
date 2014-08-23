<?php
/**
 * @var $this ProductController
 * @var $form TbActiveForm
 * @var $this ProductController
 * @var $module YcmModule
 * @var $globalPriceChangeForm GlobalPriceChangeForm
 */

$module = $this->getModule();

?>

<div class="row-fluid">
    <div class="span12">
        <div class="alert alert-info">
            <?php echo Yii::t('core', 'Set the percent quantity from -100 to +infinity.') ?>
        </div>

        <?php
            $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
                'type' => 'horizontal',
                'inlineErrors' => false,
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'onSubmit' => 'return confirm("' . Yii::t('core', 'Do u sure that u want yo change global price?') . '");',
                ),
            ));
        ?>

        <?php $module->createWidget($form, $globalPriceChangeForm, 'ratio') ?>

        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'buttons' => array(
                    array(
                        'buttonType' => 'submit',
                        'type' => 'primary',
                        'label' => Yii::t('core', 'Update all prices'),
                        'htmlOptions' => array('name' => '_save')
                    )
                )
            ));
            ?>
        </div>
        <?php $this->endWidget() ?>
    </div>
</div>