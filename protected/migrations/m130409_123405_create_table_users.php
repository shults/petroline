<?php

class m130409_123405_create_table_users extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{users}}', array(
            'user_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'role' => 'ENUM(\'administrator\', \'moderator\') DEFAULT \'moderator\' NOT NULL',
            'email' => 'VARCHAR(100) NOT NULL',
            'password' => 'CHAR(40) NOT NULL',
            'first_name' => 'VARCHAR(50) NOT NULL',
            'last_name' => 'VARCHAR(50) NOT NULL',
            'created_at' => 'DATETIME',
        ));
    }

    public function down()
    {
        $this->dropTable('{{users}}');
        return true;
    }

}