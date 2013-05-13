<?php

class m130513_184651_orders_add_column_language_id extends CDbMigration
{

    public function up()
    {
        $this->addColumn('{{orders}}', 'language_id', 'SMALLINT UNSIGNED NOT NULL');
        $this->createIndex('language_id', '{{orders}}', 'language_id');
        $this->addForeignKey('orders_language_id_fk', '{{orders}}', 'language_id', '{{languages}}', 'language_id', 
                'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('orders_language_id_fk', '{{orders}}');
        $this->dropColumn('{{orders}}', 'language_id');
        return true;
    }

}