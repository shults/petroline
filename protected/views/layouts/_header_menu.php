<table cellpadding="0" cellspacing="0" border="0" class="header_menu">
    <tr>
        <?php
        $i = 0;
        foreach ($items = $this->getMainMenuItems() as $item):
            $i++;
            ?>
            <td>
                <a href="<?php echo $item['url']; ?>" <?php echo $item['class'] ?> ><?php echo $item['label'] ?></a>
            </td>
            <?php if ($i !== count($items)): ?>
                <td class="menu_separator">
                    <img src="/images/menu_separator.gif" border="0" alt="" width="2" height="32">
                </td>
            <?php endif; ?>
        <?php endforeach; ?>
    </tr>
</table>