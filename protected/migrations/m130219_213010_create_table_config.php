<?php

class m130219_213010_create_table_config extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{config}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'key' => 'VARCHAR(100) NOT NULL',
            'widget' => 'VARCHAR(100) NOT NULL DEFAULT \'textArea\'',
            'title' => 'VARCHAR(100) NOT NULL',
            'value' => 'TEXT NOT NULL'
        ));
        $this->createIndex('language_id', '{{config}}', 'language_id');
        $this->createIndex('language_id_key', '{{config}}', 'language_id,key', true);
        $this->addForeignKey('config_language_id_fk', '{{config}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{config}}');
        return true;
    }

}