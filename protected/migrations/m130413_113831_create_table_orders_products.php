<?php

class m130413_113831_create_table_orders_products extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{orders_products}}', array(
            'order_id' => 'INT UNSIGNED NOT NULL',
            'product_id' => 'INT UNSIGNED NOT NULL',
            'number_of_products' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 1',
        ));
        $this->createIndex('order_id', '{{orders_products}}', 'order_id');
        $this->createIndex('product_id', '{{orders_products}}', 'product_id');
        $this->createIndex('order_id,product_id', '{{orders_products}}', 'order_id,product_id', true);
        $this->addForeignKey('orders_products_order_id_fk', '{{orders_products}}', 'order_id', '{{orders}}', 'order_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('orders_products_product_id_fk', '{{orders_products}}', 'product_id', '{{products}}', 'product_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{orders_products}}');
        return false;
    }

}