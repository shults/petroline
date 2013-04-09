<?php

class m130219_123123_create_table_pages extends CDbMigration {

    public function up() {
        $this->createTable('{{pages}}', array(
            'page_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT true',
            'url' => 'VARCHAR(100) NOT NULL UNIQUE',
            'title' => 'VARCHAR(255) NOT NULL',
            'text' => 'text',
            'meta_title' => 'VARCHAR(255)',
            'meta_keywords' => 'VARCHAR(255)',
            'meta_description' => 'text',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ));
    }

    public function down() {
        $this->dropTable('{{pages}}');
        return true;
    }

}