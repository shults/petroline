<?php
/**
 * Uses modified MessageCommand
 */
return array(
    'sourcePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'messagePath' => dirname(__FILE__).DIRECTORY_SEPARATOR.'messages',
    'languages' => array('ru', 'uk'),
    'fileTypes' => array('php', 'js'),
    'removeOld' => true,
    'overwrite' => true,
    'translator'=>array(
        'Yii::t',
        'Messager.t'
    ),
    'exclude'=>array(
        '.svn',
        '/protected/messages',
        '/protected/vendors',
        '/protected/runtime',
        '/framework',
    ),
);