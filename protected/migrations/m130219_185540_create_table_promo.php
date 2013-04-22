<?php

class m130219_185540_create_table_promo extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{promo}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'order' => 'TINYINT UNSIGNED NOT NULL DEFAULT 1',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT 1',
            'image' => 'VARCHAR(255) NOT NULL',
            'url' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT NOT NULL'
        ));
        $this->createIndex('language_id', '{{promo}}', 'language_id');
        $this->addForeignKey('promo_language_id_fk', '{{promo}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{promo}}');
        return true;
    }

}