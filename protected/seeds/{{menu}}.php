<?php

return array(
	array(
		'parent_menu_id' => 0,
		'home' => 1,
		'enabled' => 1,
		'order' => 1,
		'anchour' => 'Главная',
		'url' => 'home',
		'created_at' => new CDbExpression('NOW()'),
		'updated_at' => new CDbExpression('NOW()')
	),
	array(
		'parent_menu_id' => 0,
		'home' => 0,
		'enabled' => 1,
		'order' => 2,
		'anchour' => 'Услуги',
		'url' => 'uslugi',
		'created_at' => new CDbExpression('NOW()'),
		'updated_at' => new CDbExpression('NOW()')
	),
	array(
		'parent_menu_id' => 0,
		'home' => 0,
		'enabled' => 1,
		'order' => 3,
		'anchour' => 'Контакты',
		'url' => 'contact',
		'created_at' => new CDbExpression('NOW()'),
		'updated_at' => new CDbExpression('NOW()')
	),
);