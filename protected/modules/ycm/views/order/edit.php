<div class="row-fluid">
    <div class="span10">
        <?php
        /* @var $this OrderController */
        /* @var $form TbActiveForm */
        /* @var $order Order */
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'type' => 'horizontal',
            'inlineErrors' => false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        ));
        echo $form->textFieldRow($order, 'customer_full_name', array('class' => 'span7'));
        echo $form->dropDownListRow($order, 'payment_id', CMap::mergeArray(
                        array(
                    '' => Yii::t('payment', '-=Select payment method=-')
                        ), CHtml::listData(Payment::model()->findAll(), 'payment_id', 'title')
        ));
        echo $form->dropDownListRow($order, 'delivery_id', CMap::mergeArray(
                        array(
                    '' => Yii::t('delivery', '-=Select delivery method=-')
                        ), CHtml::listData(Delivery::model()->findAll(), 'delivery_id', 'title')
        ));
        echo $form->textFieldRow($order, 'customer_phone', array('class' => 'span7'));
        echo $form->textFieldRow($order, 'customer_email', array('class' => 'span7'));
        echo $form->textAreaRow($order, 'delivery_address', array('class' => 'span7', 'rows' => 5));
        echo $form->dropDownListRow($order, 'status', Order::model()->getStatusChoises());

        if (!$order->getIsNewRecord()) {
            $this->renderPartial('_order_products', array(
                'order' => $order
            ));
        }

        if ($order->getIsNewRecord()) {
            $buttons = array(
                array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => Yii::t('order', 'Save and add products'),
                    'htmlOptions' => array('name' => '_save_and_resume_addproducts')
                ),
            );
        } else {
            $buttons = array(
                array(
                    'buttonType' => 'submit',
                    'type' => 'primary',
                    'label' => Yii::t('order', 'Save'),
                    'htmlOptions' => array('name' => '_save')
                ),
                array(
                    'buttonType' => 'link',
                    'type' => 'normal',
                    'url' => array('order/addProductToOrder', 'order_id' => $order->order_id),
                    'label' => Yii::t('order', 'Add product to order')
                )
            );
        }
        ?>
        <div class="form-actions">
            <?
            $this->widget('bootstrap.widgets.TbButtonGroup', array(
                'type' => '',
                'buttons' => $buttons
            ));
            ?>
        </div>
        <?php $this->endWidget() ?>
    </div>
</div>