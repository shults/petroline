<div class="cart_bg" align="right">
    <div>
        <b>
            <a href="<?php echo CHtml::normalizeUrl(array('cart/view')) ?>">
                <?php echo Yii::t('cart', 'In your cart <strong>{n}</strong>', array(Cart::get()->getNumberOfProduct())) ?>
                <?php echo Yii::t('cart', 'n==1#item|n>1#items', array(Cart::get()->getNumberOfProduct())); ?>
            </a>
        </b> 
    </div>
</div>