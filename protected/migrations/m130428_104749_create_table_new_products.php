<?php

class m130428_104749_create_table_new_products extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{new_products}}', array(
            'product_id' => 'INT UNSIGNED NOT NULL PRIMARY KEY',
            'order' => 'TINYINT UNSIGNED NOT NULL DEFAULT 1',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL'
        ));
        $this->addForeignKey('new_products_product_id_fk', '{{new_products}}', 'product_id', '{{products}}', 
               'product_id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('new_products_language_id_fk', '{{new_products}}', 'language_id', '{{languages}}', 
               'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{new_products}}');
        return true;
    }

}