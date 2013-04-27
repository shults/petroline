<?php
$error = Yii::app()->errorHandler->error;
?>
<h1><?php echo $error['code']?></h1>
<h3><?php echo $error['message']?></h3>