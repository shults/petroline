<?php
$error = Yii::app()->errorHandler->error;
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php echo Yii::t('common', 'Error') . ' ' . $error['code']?></title>
        <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
        <link type="text/css" rel="stylesheet" href="/css/404.css" media="all" />
    </head>
    <body>
        <div id="wrap">
            <div id="header">
                <h1>
                    <a href="/"><?php echo Yii::t('common', 'Error') ?></a>
                </h1>
                <form action="<?php echo CHtml::normalizeUrl(array('catalog/search')) ?>">
                    <fieldset>
                        <input class="search" type="text" name="q"  value="<?php echo Yii::t('common', 'Search...') ?>" onclick="value = ''" />
                        <input class="search" type="submit" value="<?php echo Yii::t('common', 'Search') ?>" />
                    </fieldset>
                </form>
                <div class="clear"></div>
            </div>
            <div id="left">
                <p id="big">
                    <?php echo $error['code'] ?>
                </p>
                <p id="ohno">
                    <?php echo $error['message'] ?>
                </p>
            </div>
            <div id="right">
                <h3>
                    <?php echo Yii::t('common', 'Try these other suggestions:') ?>
                </h3>
                <?php
                    $this->widget('zii.widgets.CMenu', array(
                        'items' => $this->getMainMenuItems()
                    ));
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </body>
</html>