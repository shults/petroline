<?php

$tableNames = array(
    '{{users}}',
	//'{{pages}}',
	'{{config}}',
    '{{deliveries}}',
    '{{payments}}',
    '{{categories}}',
    '{{products}}',
    '{{orders}}',
    '{{orders_products}}',
	//'{{menu}}',
	//'{{promo}}',
	//'{{carousel}}',
);
foreach ($tableNames as $tableName) {
	echo Yii::t('console_seed', "Inserting data into table \"{tableName}\" ...\n", array(
		'{tableName}' => Yii::app()->db->getSchema()->getTable($tableName)->name,
	));
	$this->resetTable($tableName);
	$this->loadFixture($tableName);
}