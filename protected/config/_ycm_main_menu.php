<?php

return array(
    array(
        'class' => 'bootstrap.widgets.TbMenu',
        'items' => array(
            array(
                'label' => Yii::t('catalog', 'Orders'),
                'url' => array('order/index'),
            ),
            array(
                'label' => Yii::t('catalog', 'Catalog'),
                'items' => array(
                    array(
                        'label' => 'Категории',
                        'url' => array('model/list', 'name' => 'Categories'),
                    ),
                    array(
                        'label' => Yii::t('catalog', 'Products'),
                        'url' => array('model/list', 'name' => 'Products'),
                    ),
                )
            ),
            array(
                'label' => 'Модули',
                'url' => '#',
                'items' => array(
                    array(
                        'label' => 'Страницы',
                        'url' => array('model/list', 'name' => 'Page'),
                    ),
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
                'items' => array(
                    array(
                        'label' => 'Пользователи',
                        'url' => array('user/index'),
                    ),
                    array(
                        'label' => 'Доставка',
                        'url' => array('model/list', 'name' => 'Delivery'),
                    ),
                    array(
                        'label' => 'Оплата',
                        'url' => array('model/list', 'name' => 'Payment'),
                    ),
                    array(
                        'label' => 'Конфигурация',
                        'url' => array('model/list', 'name' => 'Config'),
                    ),
                )
            ),
        )
    ),
    array(
        'class' => 'bootstrap.widgets.TbMenu',
        'htmlOptions' => array('class' => 'pull-right'),
        'items' => array(
            array(
                'label' => Yii::t('user', 'Profile'),
                'url' => array('user/profile'),
            ),
            array(
                'label' => Yii::t('YcmModule.ycm', 'Logout') . ' (' . Yii::app()->user->first_name . ')',
                'url' => array('/' . $this->module->name . '/default/logout'),
            ),
        ),
    ),
);
