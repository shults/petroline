<tr>
    <td>
        <table border="0" width="100%" cellspacing="0" cellpadding="0"  class="infoBoxHeading_table">
            <tr>
                <td  class="infoBoxHeading_b">
                    <table cellpadding="0" cellspacing="0" border="0" class="infoBoxHeading_r">
                        <tr><td class="infoBoxHeading_l">
                                <table cellpadding="0" cellspacing="0" border="0" class="infoBoxHeading_tl">
                                    <tr><td class="infoBoxHeading_tr">
                                            <table cellpadding="0" cellspacing="0" border="0" class="infoBoxHeading_bl">
                                                <tr><td class="infoBoxHeading_td infoBoxHeading_br"><?php echo Yii::t('catalog', 'Categories') ?></td></tr>
                                            </table>
                                        </td></tr>
                                </table>
                            </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0"  class="infoBox_table">
            <tr>
                <td  class="infoBox_td"><table border="0" width="100%" cellspacing="0" cellpadding="0"  class="infoBoxContents2_table">
                        <tr>
                            <td  class="boxText">
                                <?php
                                $this->widget('zii.widgets.CMenu', array(
                                    'items' => $this->categoryItems,
                                    'itemCssClass' => 'bg_list',
                                    'htmlOptions' => array(
                                        'class' => 'categories'
                                    )
                                ));
                                ?>
                                <? /* ?><ul class="categories">
                                  <li class="bg_list">
                                  <a href="<?php echo CHtml::normalizeUrl(array('catalog/category')) ?>">Sound devices</a>
                                  </li>
                                  <ul class="categories">
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  </ul>
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  <li class="bg_list">
                                  <a href="#">Sound devices</a>
                                  </li>
                                  <li class="bg_list">
                                  <a href="#">House wears</a>
                                  </li>
                                  </ul>
                                  <? */ ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>