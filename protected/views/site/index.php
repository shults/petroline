<?php
/* @var $this SiteController */
$productsPerRow = 3;
$numberOfRows = ceil(count($newProducts) / $productsPerRow);
$ceilWidth = floor(100 / $productsPerRow);
?>
<table cellpadding="0" cellspacing="0" border="0" align="center" class="content_wrapper_table">
    <tr>
        <td class="content_wrapper_td">	
            <table border="0" width="100%" cellspacing="0" cellpadding="0"  class="cont_heading_table">
                <tr>
                    <td  class="infoBoxHeading_td infoBoxHeading_br"><?php echo Yii::t('catalog', 'New products') ?></td>
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
                                                    <?php if (isset($newProducts[$cell])): ?>
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
                                                                                                <a href="<?php echo $newProducts[$cell]->item->getFrontUrl(); ?>">
                                                                                                    <img src="<?php echo $newProducts[$cell]->getImageUrl(140, 130); ?>" border="0" alt="<?php echo $newProducts[$cell]->item->title ?>" title="<?php echo $newProducts[$cell]->item->title ?>" width="140" height="130">
                                                                                                </a>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table> 
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="name name2_padd">
                                                                                    <div style="height: 34px; overflow: hidden;">
                                                                                        <a href="<?php echo $newProducts[$cell]->item->getFrontUrl(); ?>"><?php echo $newProducts[$cell]->item->title ?></a>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                            <tr><td class="price2_padd"><span class="productSpecialPrice"><?php echo $newProducts[$cell]->item->getPrice() ?></span></td></tr>
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
                                                    <td class="prod_line_x padd_gg" colspan="7">
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
        </td>
    </tr>
</table>
<table border="0" align="center" cellspacing="0" cellpadding="0" class="content_wrapper4_table">
    <tbody>
        <tr>
            <td class="content_wrapper4_td">
                <table border="0" cellspacing="0" cellpadding="0" class="content_wrapper2_table">
                    <tbody>
                        <tr>
                            <td class="content_wrapper2_td">
                                <div class="main">
                                    <div id="product_description" class="desc2">
                                        <?php echo Config::get('main_page_content') ?>
                                    </div>
                                </div>
                                <div style="clear:both;"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>		
            </td>
        </tr>
    </tbody>
</table>