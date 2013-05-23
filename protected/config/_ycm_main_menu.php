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
                        'url' => array('product/index'),
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
                    /*array(
                        'label' => 'Промо',
                        'url' => array('model/list', 'name' => 'Promo'),
                    ),*/
                    array(
                        'label' => 'Слайдер',
                        'url' => array('model/list', 'name' => 'Slider'),
                    ),
                    array(
                        'label' => 'Новые товары',
                        'url' => array('newProduct/index'),
                    )
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
                        'label' => Yii::t('language', 'Languages'),
                        'url' => array('model/list', 'name' => 'Language'),
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
                    array(
                        'label' => 'Очистить кеш',
                        'url' => array('default/flushCache')
                    )
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
                'label' => Yii::t('language', 'Language') . ' (' . Yii::app()->lang->title . ')',
                'items' => Language::getMenuItems()
            ),
            array(
                'label' => Yii::t('YcmModule.ycm', 'Logout') . ' (' . Yii::app()->user->first_name . ')',
                'url' => array('/' . $this->module->name . '/default/logout'),
            ),
        ),
    ),
);
