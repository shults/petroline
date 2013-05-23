<?php
/* @var $this CatalogController */
/* @var $category Categories */
$categoryItemsPerRow = Yii::app()->params['categoryItemsPerRow'];
$subCategoryRows = ceil(count($category->children) / $categoryItemsPerRow);
$rowWidth = 100 / $categoryItemsPerRow;
?>
<?php if ($category->children): ?>
    <table border="0" align="center" cellspacing="0" cellpadding="0" class="content_wrapper_table">
        <tbody>
            <tr>
                <td class="content_wrapper_td">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="cont_heading_table">
                        <tbody>
                            <tr>
                                <td class="infoBoxHeading_td infoBoxHeading_br">
                                    <?php echo Yii::t('categories', 'Subcategories') ?> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" class="content_wrapper3_table">
                        <tbody>
                            <tr>
                                <td class="content_wrapper3_td">		
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="main sub_category">
                                        <tbody>
                                            <?php for ($row = 1; $row <= $subCategoryRows; $row++): ?>
                                                <tr>
                                                    <?php for ($cell = ($row - 1) * $categoryItemsPerRow; $cell < $row * $categoryItemsPerRow; $cell++): ?>
                                                        <?php if (isset($category->children[$cell])): ?>
                                                            <?php
                                                            /* @var $subCategory Categories */
                                                            $subCategory = $category->children[$cell];
                                                            ?>
                                                            <td width="<?= $rowWidth ?>%" align="center">
                                                                <table border="0" align="center" cellspacing="0" cellpadding="0" class="prod2_table">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td class="prod2_td">
                                                                                <table border="0" cellspacing="0" cellpadding="0" class="wrapper_box">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td class="pic4_padd">
                                                                                                <table border="0" align="center" cellspacing="0" cellpadding="0" class="wrapper_pic_table">
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td class="wrapper_pic_td">
                                                                                                                <a href="<?php echo $subCategory->getFrontUrl() ?>">
                                                                                                                    <img width="100" border="0" height="92" title="<?php echo $subCategory->title ?>" alt="<?php echo $subCategory->title ?>" src="<?php echo $subCategory->getImageUrl(100, 92) ?>">
                                                                                                                </a>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td class="name name4_padd">
                                                                                                <b>
                                                                                                    <a href="<?php echo $subCategory->getFrontUrl() ?>"><?php echo $subCategory->title ?></a>
                                                                                                </b>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table> 
                                                            </td>
                                                        <?php else: ?>
                                                            <td width="<?= $rowWidth ?>%" align="center">
                                                            </td>
                                                        <?php endif; ?>
                                                        <?php if ($cell < $row * $categoryItemsPerRow - 1): ?>
                                                            <td>
                                                                <img width="1" border="0" height="1" alt="" src="/images/spacer.gif">
                                                            </td>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </tr>
                                                <?php if ($row < $subCategoryRows): ?>
                                                    <tr>
                                                        <td colspan="<?php echo($categoryItemsPerRow * 2 - 1); ?>" class="prod_line_x">
                                                            <img width="1" border="0" height="18" alt="" src="/images/spacer.gif">
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
<?php endif; ?>

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
                        <td class="infoBoxHeading_td infoBoxHeading_br"><?php echo Yii::t('products', 'Products') ?></td>
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
                <?php if ($category->description): ?>
                    <table border="0" align="center" cellspacing="0" cellpadding="0" class="content_wrapper_table" style="margin-bottom: 10px;">
                        <tbody>
                            <tr>
                                <td class="content_wrapper_td">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="cont_heading_table">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="desc2">
                                                        <?php echo $category->description ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif; ?>
                <div class="clear"></div>
            </td>
        </tr>
    </table>
<?php endif; ?>