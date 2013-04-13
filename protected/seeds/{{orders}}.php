<?php

return array(
    array(
        'order_id' => 1,
        'payment_id' => 1,
        'delivery_id' => 1,
        'customer_full_name' => 'Петров Петр Петрович',
        'customer_phone' => '+380981234567',
        'customer_email' => 'petrov_p@i.ua',
        'delivery_address' => 'г. Киев, ул. Кахи-Каладзе, 13, кв. 15',
        'status' => 'executed',
        'incoming_date' => new CDbException('NOW()'),
    ),
    array(
        'order_id' => 2,
        'payment_id' => 1,
        'delivery_id' => 1,
        'customer_full_name' => 'Иванов Инва Иванович',
        'customer_phone' => '+380991234567',
        'customer_email' => 'ivanov_i@i.ua',
        'delivery_address' => 'г. Киев, ул. Кахи-Каладзе, 13, кв. 14',
        'status' => 'not_processed',
        'incoming_date' => new CDbException('NOW()'),
    )
);