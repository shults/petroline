<?php

class m130219_213010_create_table_config extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{config}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'key' => 'VARCHAR(100) NOT NULL UNIQUE',
            'widget' => 'VARCHAR(100) NOT NULL DEFAULT \'textArea\'',
            'title' => 'VARCHAR(100) NOT NULL',
            'value' => 'TEXT NOT NULL'
        ));
    }

    public function down()
    {
        $this->dropTable('{{config}}');
        return true;
    }

}