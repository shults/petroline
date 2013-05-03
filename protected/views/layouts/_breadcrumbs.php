<?php
$breadCrumbs = $this->widget('ext.TbBreadcrumbs', array(
    'links' => $this->breadcrumbs,
    'home' => Yii::t('breadcrumbs', 'Home')
));
?>