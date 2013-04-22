<?php

class m130219_185003_create_table_carousel extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{carousel}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'order' => 'TINYINT UNSIGNED NOT NULL DEFAULT 1',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT 1',
            'image' => 'VARCHAR(255) NOT NULL',
            'url' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255)'
        ));
        $this->createIndex('language_id', '{{carousel}}', 'language_id');
        $this->addForeignKey('carousel_language_id_fk', '{{carousel}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{carousel}}');
        return true;
    }

}