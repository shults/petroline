<?php

class m130219_185540_create_table_promo extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{promo}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'order' => 'TINYINT UNSIGNED NOT NULL DEFAULT 1',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT 1',
            'image' => 'VARCHAR(255) NOT NULL',
            'url' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT NOT NULL'
        ));
    }

    public function down()
    {
        $this->dropTable('{{promo}}');
        return true;
    }

}