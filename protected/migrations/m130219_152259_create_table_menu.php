<?php

class m130219_152259_create_table_menu extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{menu}}', array(
            'menu_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'parent_menu_id' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
            'home' => 'BOOLEAN NOT NULL DEFAULT false',
            'enabled' => 'BOOLEAN NOT NULL DEFAULT true',
            'order' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 1',
            'anchour' => 'VARCHAR(100) NOT NULL',
            'url' => 'VARCHAR(100) NOT NULL UNIQUE',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME'
        ));
    }

    public function down()
    {
        $this->dropTable('{{menu}}');
        return true;
    }

}