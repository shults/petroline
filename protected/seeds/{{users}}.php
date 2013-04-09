<?php

return array(
    array(
        'user_id' => 1,
        'role' => 'administrator',
        'email' => 'admin@petroline.com.ua',
        'password' => sha1('admin'),
        'first_name' => 'Admin',
        'last_name' => 'Superadmin',
        'created_at' => new CDbExpression('NOW()'),
    ),
    array(
        'user_id' => 2,
        'role' => 'moderator',
        'email' => 'moder@petroline.com.ua',
        'password' => sha1('moder'),
        'first_name' => 'Moder',
        'last_name' => 'Supermoder',
        'created_at' => new CDbExpression('NOW()'),
    )
);
