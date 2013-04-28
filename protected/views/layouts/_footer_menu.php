<div class="footer_wrapper_tl">
    <div class="footer_wrapper_tr">
        <div class="footer_wrapper_rep fs_lh">
            <table cellpadding="0" cellspacing="0" border="0" align="center">
                <tr>
                    <?php
                    /* @var $this FrontController */
                    $i = 0;
                    foreach ($items = $this->getMainMenuItems() as $item):
                        $i++;
                        ?>
                        <td><a href="<?php echo $item['url'] ?>" <?php echo $item['class'] ?> ><?php echo $item['label'] ?></a></td>
                        <?php if ($i !== count($items)): ?>
                            <td class="menu_separator"><img src="/images/footer_separator.gif" border="0" alt="" width="1" height="10"></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
            </table>
        </div>
    </div>
</div>