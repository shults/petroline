<?php

class m130413_092045_create_table_delivery extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{deliveries}}', array(
            'delivery_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'consider_price' => 'BOOLEAN NOT NULL DEFAULT 0', // учитывать цену
            'price' => 'DECIMAL(8,2)',
            'show_order_comment' => 'BOOLEAN NOT NULL DEFAULT 0', // показывать комментарий во время заказа
            'order_comment' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'deleted' => 'BOOLEAN NOT NULL DEFAULT 0'
        ));
        $this->createIndex('language_id', '{{deliveries}}', 'language_id');
        $this->addForeignKey('deliveries_language_id_fk', '{{deliveries}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{deliveries}}');
        return true;
    }

}