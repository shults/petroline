<?php

return array(
    array(
        'class' => 'bootstrap.widgets.TbMenu',
        'items' => array(
            array(
                'label' => Yii::t('catalog', 'Catalog'),
                'items' => array(
                    array(
                        'label' => Yii::t('catalog', 'Categories'),
                        'url' => array('model/list', 'name' => 'ProductCategory'),
                    ),
                    array(
                        'label' => Yii::t('catalog', 'Manufacturers'),
                        'url' => array('model/list', 'name' => 'ProductManufacturer'),
                    ),
                    array(
                        'label' => Yii::t('catalog', 'Products'),
                        'url' => array('model/list', 'name' => 'Product'),
                    ),
                )
            ),
            array(
                'label' => 'Страницы',
                'url' => array('model/list', 'name' => 'Page'),
            ),
            /*             * array(
              'label' => 'Пункты меню',
              'url' => array('model/list', 'name' => 'Menu'),
              ), */
            array(
                'label' => 'Модули',
                'url' => '#',
                'items' => array(
                    array(
                        'label' => 'Карусель',
                        'url' => array('model/list', 'name' => 'Carousel'),
                    ),
                    array(
                        'label' => 'Промо',
                        'url' => array('model/list', 'name' => 'Promo'),
                    ),
                )
            ),
            array(
                'label' => 'Настройки',
                'url' => array('model/list', 'name' => 'Config'),
            ),
        )
    ),
    array(
        'class' => 'bootstrap.widgets.TbMenu',
        'htmlOptions' => array('class' => 'pull-right'),
        'items' => array(
            array(
                'label' => Yii::t('YcmModule.ycm', 'Logout'),
                'url' => array('/' . $this->module->name . '/default/logout'),
            ),
        ),
    ),
);
