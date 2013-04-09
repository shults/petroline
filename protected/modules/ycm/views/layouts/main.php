<?php
/* @var $this AdminController */

$cs = Yii::app()->clientScript;
$baseUrl = $this->module->assetsUrl;
$cs->registerCoreScript('jquery');
$cs->registerCssFile($baseUrl . '/css/styles.css');
?>
<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language; ?>">
    <head>
        <meta charset="utf-8">
        <meta name="robots" content="NONE,NOARCHIVE" />
        <title><?php print Yii::t('YcmModule.ycm', 'Administration') ?></title>
        <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php
        if (!Yii::app()->user->isGuest) {
            $this->widget('bootstrap.widgets.TbNavbar', array(
            'type' => 'inverse', // null or 'inverse'
            'brand' => Yii::t('YcmModule.ycm', 'Administration'),
            'brandUrl' => Yii::app()->createUrl('/' . $this->module->name),
            'collapse' => true, // requires bootstrap-responsive.css
            'fluid' => true,
            'items' => require(Yii::getPathOfAlias('application.config') . '/_ycm_main_menu.php')
            ));
        }
        ?>

        <?php if (!empty($this->breadcrumbs)): ?>
            <div class="container-fluid">
                <?php
                $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
                    'links' => $this->breadcrumbs,
                    'separator' => '/',
                    'homeLink' => CHtml::link(Yii::t('YcmModule.ycm', 'Home'), Yii::app()->createUrl('/' . $this->module->name)),
                ));
                ?>
            </div>
        <?php endif ?>

        <div class="container-fluid">
            <?php
            $this->widget('bootstrap.widgets.TbAlert', array(
                'block' => true,
                'fade' => true,
                'closeText' => '&times;',
            ));
            ?>

            <?php echo $content; ?>

        </div>
    </body>
</html>