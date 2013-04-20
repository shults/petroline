<?php

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => $widgetId = get_class($model) . '-id-form',
    'type' => 'horizontal',
    'inlineErrors' => false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
));

//echo $this->module->getButtons();

$this->widget('bootstrap.widgets.TbTabs', array(
    'type' => 'tabs',
    'tabs' => $this->getTabs($form, $model),
));

$this->endWidget($widgetId);