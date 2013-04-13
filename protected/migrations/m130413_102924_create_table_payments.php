<?php

class m130413_102924_create_table_payments extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{payments}}', array(
            'payment_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT',
            'deleted' => 'BOOLEAN NOT NULL DEFAULT 0',
        ));
    }

    public function down()
    {
        $this->dropTable('{{payments}}');
        return true;
    }

}