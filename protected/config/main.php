<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Компания',
    'language' => 'ru',
    'preload' => array(),
    'aliases' => array(
        'xupload' => 'application.modules.ycm.extensions.xupload',
        'ycm' => 'application.modules.ycm',
        'chosen' => 'application.modules.ycm.extensions.chosen',
    ),
    'import' => array(
        'application.models.*',
        'application.components.*',
    ),
    'modules' => array(
        'ycm' => array(
            'homeUrl' => array('order/index'),
            'registerModels' => array(
                'application.models.*',
            ),
            'uploadCreate' => true,
            'redactorUpload' => true,
        ),
    ),
    // application components
    'components' => array(
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
        ),
        'lang' => array(
            'class' => 'FrontLanguageComponent',
        ),
        'urlManager' => array(
            'class' => 'UrlManager',
            'urlFormat' => 'path',
            'showScriptName' => false,
            'rules' => array(
                array(
                    'class' => 'application.routes.CategoryRule'
                ),
                '<language:(\w{2})>' => 'site/index',
                '' => 'site/index',
                '<language:(\w{2})>/' => 'site/index',
                '<language:(\w{2})>/search' => 'catalog/search',
                'search' => 'catalog/search',
                '<language:(\w{2})>/cart/<action:\w+>' => 'cart/<action>',
                'cart/<action:\w+>' => 'cart/<action>',
                '<language:(\w{2})>/<page:(delivery_payment|about_us|contacts)>' => 'site/<page>',
                '<page:(delivery_payment|about_us|contacts)>' => 'site/<page>',
                '<language:(\w{2})>/contact' => 'site/contact',
                '<language:(\w{2})>/<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),
        'db' => require(dirname(__FILE__) . '/_db_main.php'),
        'errorHandler' => array(
            'errorAction' => 'error/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        'adminEmail' => 'webmaster@example.com',
        'imagestore' => 'uploads',
        'itemsPerPage' => 9,
        'itemsPerLine' => 3,
        'categoryItemsPerRow' => 5,
    ),
);
