<?php

class m130219_123122_create_table_languages extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{languages}}', array(
            'language_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'code' => 'CHAR(2) NOT NULL',
            'title' => 'VARCHAR(100) NOT NULL',
            'flag' => 'VARCHAR(10) NOT NULL',
            'default' => 'BOOLEAN NOT NULL DEFAULT 0',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT 1',
            'deleted' => 'BOOLEAN NOT NULL DEFAULT 0'
        ));
        $this->createIndex('code', '{{languages}}', 'code', true);
    }

    public function down()
    {
        $this->dropTable('{{languages}}');
        return true;
    }

}