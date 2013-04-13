<?php

class m130413_113822_create_table_orders extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{orders}}', array(
            'order_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'payment_id' => 'SMALLINT UNSIGNED NOT NULL',
            'delivery_id' => 'SMALLINT UNSIGNED NOT NULL',
            'customer_full_name' => 'VARCHAR(255) NOT NULL',
            'customer_phone' => 'CHAR(15) NOT NULL',
            'customer_email' => 'VARCHAR(100)',
            'delivery_address' => 'VARCHAR(255)',
            //not_processed => не обработан
            //rejected => отказ
            //executed => выполнен
            //performed => в процессе выполнения
            'status' => 'ENUM(\'not_processed\', \'rejected\', \'executed\', \'performed\') DEFAULT \'not_processed\'',
            'incoming_date' => 'DATETIME NOT NULL', // дата поступления
            'total_price' => 'DECIMAL(8,2) NOT NULL'
        ));
    }

    public function down()
    {
        $this->dropTable('{{orders}}');
        return true;
    }

}