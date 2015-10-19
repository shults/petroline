<?php
/* @var $product CartProduct */
/* @var $this CartController */
$numberOfProducts = count($products);
$paymentMethods = CMap::mergeArray(
                array('' => Yii::t('payment', '-=Select payment method=-'))
                , CHtml::listData(Payment::model()->findAll(), 'payment_id', 'title'));
$deliveryMethods = CMap::mergeArray(array(
            '' => Yii::t('delivery', '-=Select delivery method=-')
                ), CHtml::listData($arDeliveryMethods = Delivery::model()->findAll(), 'delivery_id', 'title'));
$resumeBuying = Yii::app()->request->urlReferrer ? Yii::app()->request->urlReferrer : '/';
$methods = array();
foreach ($arDeliveryMethods as $method) {
    $methods[] = $method->attributes;
}
Yii::app()->clientScript->registerScript('make-order', "
    jQuery('#Order_delivery_id').change(function() {
        var deliveryMethods = " . json_encode($methods) . ";
        var totalPrice = " . Cart::get()->getTotalPrice(false) . ";
        var currency = '" . Yii::t('common', 'UAH') . "';
        var delivery_id = jQuery(this).val();
        console.log(deliveryMethods);
        //alert(delivery_id);
        jQuery('#deliveryPriceTable').hide();
        jQuery('#totalPrice').html(totalPrice.toFixed(2) + ' ' + currency);
        jQuery('#priceDivider').hide();
        for (i in deliveryMethods) {
            var method = deliveryMethods[i];
            if (method.delivery_id == delivery_id) {
                if (method.consider_price == 1) {
                    jQuery('#deliveryPriceTable').show();
                    jQuery('#priceDivider').show();
                    jQuery('#deliveryPrice').html(parseFloat(method.price).toFixed(2) + ' ' + currency);
                    jQuery('#totalPrice').html((totalPrice + parseFloat(method.price)).toFixed(2) + ' ' + currency);
                } else if (method.show_order_comment == 1) {
                    jQuery('#deliveryPriceTable').show();
                    jQuery('#priceDivider').show();
                    jQuery('#deliveryPrice').html(method.order_comment);
                    jQuery('#totalPrice').html(totalPrice.toFixed(2) + ' ' + currency);
                }
            }
        }   
    });
");
?>
<table border="0" align="center" cellspacing="0" cellpadding="0" class="content_wrapper4_table">
    <tbody>
        <tr>
            <td class="content_wrapper4_td">
                <?php if (!Cart::get()->isEmpty()): ?>
                    <table width="" border="0" cellspacing="0" cellpadding="0" class="tableBox_shopping_cart main">
                        <tbody>
                            <tr>
                                <td align="center"><?php echo Yii::t('cart', 'Image'); ?></td>  
                                <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>    
                                <td align="center"><?php echo Yii::t('cart', 'Title'); ?></td>  
                                <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>    
                                <td align="center"><?php echo Yii::t('cart', 'Q-ty'); ?></td>
                                <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                <td align="center" style="width:100px;"><?php echo Yii::t('cart', 'Summ'); ?></td>  
                                <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                <td align="center"><?php echo Yii::t('cart', 'Delete'); ?></td>  
                            </tr>
                            <tr>
                                <td colspan="9" class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                            </tr>
                            <?php foreach ($products as $key => $product): ?>
                                <tr>
                                    <td align="left">
                                        <a href="<?php echo CHtml::normalizeUrl(array('catalog/product', 'product_id' => $product->product->product_id)) ?>">
                                            <img width="50" border="0" height="50" title="<?php echo $product->product->title ?>" alt="<?php echo $product->product->title ?>" src="<?php echo $product->getImageUrl(50, 50); ?>">
                                        </a>
                                    </td>  
                                    <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                    <td>
                                        <a href="<?php echo CHtml::normalizeUrl(array('catalog/product', 'product_id' => $product->product->product_id)) ?>"><?php echo $product->product->title ?></a>
                                    </td>  
                                    <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                    <td align="center" class="s_cart_td" style="width:100px;">
                                        <?php echo CHtml::link('', array('cart/increment', 'product_id' => $product->product_id), array('class' => 'icon-plus', 'title' => Yii::t('cart', 'Add'))) ?>
                                        <?php echo $product->count ?>
                                        <?php echo CHtml::link('', array('cart/decrement', 'product_id' => $product->product_id), array('class' => 'icon-minus', 'title' => Yii::t('cart', 'Remove'))) ?>
                                    </td>  
                                    <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                    <td align="center" class="s_cart_td">
                                        <span class="productSpecialPrice"><?php echo $product->product->getPrice() ?></span>
                                    </td>
                                    <td class="cart_line_y padd2_vv"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td>
                                    <td align="center" class="s_cart_td">
                                        <?php echo CHtml::link('', array('cart/remove', 'product_id' => $product->product_id), array('class' => 'icon-trash', 'title' => Yii::t('cart', 'Delte from cart'))) ?>
                                    </td>  
                                </tr>
                                <?php if ($key < $numberOfProducts - 1): ?>
                                    <tr><td colspan="9" class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></td></tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td align="right" class="cart_total_left">
                                    <?php echo Yii::t('cart', 'Sub-Total:') ?>&nbsp;&nbsp;&nbsp;
                                    <span class="productSpecialPrice" style="color: #0BB1E8; font-weight: bold;"><?php echo Cart::get()->getTotalPrice() ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <table id="deliveryPriceTable" border="0" cellspacing="0" cellpadding="0" style="display: none;">
                        <tbody>
                            <tr>
                                <td align="right" class="cart_total_left">
                                    <?php echo Yii::t('cart', 'Delivery price:') ?>&nbsp;&nbsp;&nbsp;
                                    <span class="productSpecialPrice" id="deliveryPrice" style="color: #0BB1E8; font-weight: bold;"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart_line_x padd2_gg" id="priceDivider" style="display: none;"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td align="right" class="cart_total_left">
                                    <?php echo Yii::t('cart', 'Total price:') ?>&nbsp;&nbsp;&nbsp;
                                    <span class="productSpecialPrice" id="totalPrice" style="color: #0BB1E8; font-weight: bold;"><?php echo Cart::get()->getTotalPrice() ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <div>
                        <?php
                        $form = $this->beginWidget('system.web.widgets.CActiveForm', array(
                            'htmlOptions' => array(
                                'class' => 'form-horizontal',
                            )
                        ));
                        ?>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'customer_full_name', array('class' => 'control-label required')); ?>
                            <div class="controls">
                                <?php echo $form->textField($order, 'customer_full_name', array('class' => 'span5')); ?>
                                <?php echo $form->error($order, 'customer_full_name'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'customer_phone', array('class' => 'control-label required')); ?>
                            <div class="controls">
                                <?php echo $form->textField($order, 'customer_phone', array('class' => 'span5')); ?>
                                <?php echo $form->error($order, 'customer_phone'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'payment_id', array('class' => 'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($order, 'payment_id', $paymentMethods, array('class' => 'span5')); ?>
                                <?php echo $form->error($order, 'payment_id'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'delivery_id', array('class' => 'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->dropDownList($order, 'delivery_id', $deliveryMethods, array('class' => 'span5')); ?>
                                <?php echo $form->error($order, 'delivery_id'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'customer_email', array('class' => 'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($order, 'customer_email', array('class' => 'span5')); ?>
                                <?php echo $form->error($order, 'customer_email'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <?php echo $form->labelEx($order, 'delivery_address', array('class' => 'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textArea($order, 'delivery_address', array('class' => 'span5', 'rows' => 4)); ?>
                                <?php echo $form->error($order, 'delivery_address'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <a href="<?php echo $resumeBuying ?>" class="btn btn-primary" style="float: left; text-decoration: none;"><?php echo Yii::t('cart', 'Resume') ?></a>
                            <input type="submit" class="btn btn-primary" style="float: right;" value="<?php echo Yii::t('cart', 'Make order') ?>" />
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                <?php else: ?>
                    <div class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <p class="error"><?php echo Yii::t('cart', 'You cart is empty') ?></p>
                    <div class="cart_line_x padd2_gg"><img width="1" border="0" height="1" alt="" src="/images/spacer.gif"></div>
                    <?php endif; ?>
            </td>
        </tr>
    </tbody>
</table>