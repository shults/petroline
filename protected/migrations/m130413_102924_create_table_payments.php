<?php

class m130413_102924_create_table_payments extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{payments}}', array(
            'payment_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'title' => 'VARCHAR(255) NOT NULL',
            'description' => 'TEXT',
            'deleted' => 'BOOLEAN NOT NULL DEFAULT 0',
        ));
        $this->createIndex('language_id', '{{payments}}', 'language_id');
        $this->addForeignKey('payments_language_id_fk', '{{payments}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{payments}}');
        return true;
    }

}