<?php
/* @var $this CatalogController */
/* @var $product Products */
$productsPerRow = Yii::app()->params['itemsPerLine'];
$numberOfRows = ceil(count($products) / $productsPerRow);
$ceilWidth = floor(100 / $productsPerRow);
?>
<?php if ($products): ?>
    <table cellpadding="0" cellspacing="0" border="0" align="center" class="content_wrapper_table">
        <tr>
            <td class="content_wrapper_td">	
                <table border="0" width="100%" cellspacing="0" cellpadding="0"  class="cont_heading_table">
                    <tr>
                        <td  class="cont_heading_td"><?php echo Yii::t('product', 'Products') ?></td>
                    </tr>
                </table>
                <table cellpadding="0" cellspacing="0" border="0" class="content_wrapper3_table">
                    <tr>
                        <td class="content_wrapper3_td">		
                            <table border="0" width="" cellspacing="0" cellpadding="0" class="tableBox_output_table">
                                <tr>
                                    <td  class="main">
                                        <table border="0" width="" cellspacing="0" cellpadding="0">

                                            <?php for ($row = 0; $row < $numberOfRows; $row++): ?>
                                                <tr>
                                                    <?php for ($cell = $row * $productsPerRow; $cell < ($row + 1) * $productsPerRow; $cell++): ?>
                                                        <?php if (isset($products[$cell]) && $product = $products[$cell]): ?>
                                                            <td align="left"  style="width:<?= $ceilWidth ?>%">
                                                                <table cellpadding="0" cellspacing="0" border="0" align="center" class="prod2_table">
                                                                    <tr>
                                                                        <td class="prod2_td">
                                                                            <table cellpadding="0" cellspacing="0" border="0" class="wrapper_box">
                                                                                <tr>
                                                                                    <td class="pic2_padd">
                                                                                        <table cellpadding="0" cellspacing="0" border="0" align="center" class="wrapper_pic_table">
                                                                                            <tr>
                                                                                                <td class="wrapper_pic_td">
                                                                                                    <a href="<?php echo $product->getFrontUrl() ?>">
                                                                                                        <img src="<?php echo $product->getImageUrl(140, 130) ?>" border="0" alt="<?php echo $product->title ?>" title="<?php echo $product->title ?>" width="140" height="130">
                                                                                                    </a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table> 
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td class="name name2_padd">
                                                                                        <div style="height: 34px; overflow: hidden;">
                                                                                            <a href="<?php echo $product->getFrontUrl() ?>"><?php echo $product->title ?></a>
                                                                                        </div>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr><td class="price2_padd"><span class="productSpecialPrice"><?php echo $product->getPrice() ?></span></td></tr>
                                                                            </table>									   
                                                                        </td>
                                                                    </tr>
                                                                </table> 
                                                            </td>
                                                        <?php else: ?>
                                                            <td align="left"  style="width:<?= $ceilWidth ?>%"></td>
                                                        <?php endif; ?>
                                                        <?php if ($cell < ($row + 1) * $productsPerRow - 1): ?>
                                                            <td class="prod_line_y padd_vv"><img src="/images/spacer.gif" border="0" alt="" width="1" height="1"></td>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php if ($row < $numberOfRows - 1): ?>
                                                    <tr>
                                                        <td class="prod_line_x padd_gg" colspan="<?= (2 * $productsPerRow - 1) ?>">
                                                            <img src="images/spacer.gif" border="0" alt="" width="1" height="1">
                                                        </td>
                                                    </tr>   
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </table>
                                    </td>
                                </tr> 	
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="pagination pagination-small">
                    <?php
                    $this->widget('CLinkPager', array(
                        'pages' => $pages,
                        'header' => '',
                        'nextPageLabel' => '>',
                        'lastPageLabel' => '>>',
                        'prevPageLabel' => '<',
                        'firstPageLabel' => '<<',
                        'htmlOptions' => array(
                            'class' => ''
                        )
                    ));
                    ?>
                </div>
            </td>
        </tr>
    </table>
<?php endif; ?>