<?php
/* @var $product Products */
/* @var $this CatalogController */
/* @var $colorbox JColorBox */
$colorbox = $this->widget('ext.colorpowered.JColorBox');
$colorbox->addInstance('a.colorbox', array('rel' => 'products'));
?>
<table border="0" align="center" cellspacing="0" cellpadding="0" class="content_wrapper4_table">
    <tbody>
        <tr>
            <td class="content_wrapper4_td">
                <table border="0" cellspacing="0" cellpadding="0" class="content_wrapper2_table">
                    <tbody>
                        <tr>
                            <td class="content_wrapper2_td">
                                <div style="width:163px;" class="main prod_info">
                                    <table border="0" align="center" cellspacing="0" cellpadding="0" class="wrapper_pic_table">
                                        <tbody>
                                            <tr>
                                                <td class="wrapper_pic_td">
                                                    <a href="<?php echo $product->getImageUrl(500, 500); ?>" class="colorbox" rel="products">
                                                        <img width="140" border="0" height="130" title=" Product #203 " alt="Product #203" src="<?php echo $product->getImageUrl(140, 130); ?>">
                                                    </a>
                                                    <?php foreach ($product->images as $key => $image): ?>
                                                        <?php if ($key > 0): ?>
                                                            <a href="<?php echo $image->getImageUrl(500, 500); ?>" class="colorbox" rel="products"></a>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                    <br>
                                                    <div>
                                                        <b class="productSpecialPrice"><?php echo $product->getPrice() ?></b>
                                                    </div>
                                                    <br>
                                                    <div>
                                                        <a href="<?php echo CHtml::normalizeUrl(array('cart/add', 'product_id' => $product->product_id))?>" rel="nofollow" id="add-to-cart-button" class="btn btn-small btn-success"><?php echo Yii::t('catalog', 'Add to cart') ?></a>
                                                    </div>
                                                    <br>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>	
                                <div class="main">
                                    <div class="desc2">
                                        <?php echo $product->description ?>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>		
                <!--<div class="cart_line_x padd2_gg"><img width="1" border="0" height="2" alt="" src="/images/spacer.gif"></div>-->
            </td>
        </tr>
    </tbody>
</table>