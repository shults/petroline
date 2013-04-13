<?php

return array(
    array(
        'title' => 'Самовывоз',
        'consider_price' => 0,
        'show_order_comment' => 0,
    ),
    array(
        'title' => 'Доставка по курером по Киеву',
        'consider_price' => 1,
        'price' => 35.5,
        'show_order_comment' => 0,
    ),
    array(
        'title' => 'Новая почта (наложенный платеж)',
        'consider_price' => 0,
        'show_order_comment' => 1,
        'order_comment' => 'Стоимость доставки оплачивается по тарифам перевозчика клиентом',
    )
);
