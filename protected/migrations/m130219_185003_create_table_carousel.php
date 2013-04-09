<?php

class m130219_185003_create_table_carousel extends CDbMigration {

    public function up() {
        $this->createTable('{{carousel}}', array(
            'id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'order' => 'TINYINT UNSIGNED NOT NULL DEFAULT 1',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT 1',
            'image' => 'VARCHAR(255) NOT NULL',
            'url' => 'VARCHAR(255) NOT NULL',
            'title' => 'VARCHAR(255)'
        ));
    }

    public function down() {
        $this->dropTable('{{carousel}}');
        return true;
    }
}