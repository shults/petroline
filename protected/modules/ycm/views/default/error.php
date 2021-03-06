<?php

/**
 * @var $this DefaultController
 * @var $code string
 * @var $message string
 */

$this->pageTitle = Yii::app()->name.' - '.Yii::t('YcmModule.ycm','Error');

$this->breadcrumbs = [
    Yii::t('YcmModule.ycm', 'Error')
];

?>

<h2><?php echo Yii::t('YcmModule.ycm','Error') . ' ' . $code ?></h2>

<div class="alert alert-error">
	<?php echo CHtml::encode($message) ?>
</div>

<pre><code><?php echo CHtml::encode($trace) ?></code></pre>