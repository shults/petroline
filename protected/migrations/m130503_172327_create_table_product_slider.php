<?php

class m130503_172327_create_table_product_slider extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{product_slider}}', array(
            'slide_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'product_id' => 'INT UNSIGNED NOT NULL',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'order' => 'INT UNSIGNED NOT NULL DEFAULT 1',
            'title' => 'VARCHAR(255)'
        ));
        $this->createIndex('product_id', '{{product_slider}}', 'product_id', true);
        $this->createIndex('language_id', '{{product_slider}}', 'language_id');
        $this->createIndex('order', '{{product_slider}}', 'order');
        $this->addForeignKey('product_slider_product_id_fk', '{{product_slider}}', 'product_id', '{{products}}', 'product_id');
        $this->addForeignKey('product_slider_language_id_fk', '{{product_slider}}', 'language_id', '{{languages}}', 'language_id');
    }

    public function down()
    {
        $this->dropTable('{{product_slider}}');
        return true;
    }

}