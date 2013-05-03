<td class="search">
    <form name="search" action="<?php echo CHtml::normalizeUrl(array('catalog/search')) ?>" method="get">                        
        <input type=text name="q" style="margin-bottom: 0px;" value="<?php echo (isset($_GET['q']) && $_GET['q']) ? $_GET['q'] : Yii::t('catalog', ' Search entire store...') ?>" onblur="if (this.value == '')
                    this.value = '<?php echo Yii::t('catalog', ' Search entire store...') ?>'" onfocus="if (this.value == '<?php echo Yii::t('catalog', ' Search entire store...') ?>')
                    this.value = ''">
        <input type="submit" class="btn btn-info" value="<?php echo Yii::t('common', 'Search') ?>" />
    </form>                                        
</td>