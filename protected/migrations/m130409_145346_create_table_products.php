<?php

class m130409_145346_create_table_products extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{products}}', array(
            'product_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'category_id' => 'SMALLINT UNSIGNED NOT NULL',
            'url' => 'VARCHAR(100) NOT NULL',
            'title' => 'VARCHAR(100) NOT NULL',
            'status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'store_status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'description' => 'TEXT NOT NULL',
            'price' => 'DECIMAL(8,4)',
            'trade_pice' => 'DECIMAL(8,4)',
            'min_trade_order' => 'SMALLINT UNSIGNED',
            'meta_title' => 'VARCHAR(100)',
            'meta_description' => 'TEXT',
            'meta_keywords' => 'TEXT',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME',
            'created_by' => 'INT UNSIGNED NOT NULL',
            'updated_by' => 'INT UNSIGNED NOT NULL',
        ));
        $this->createIndex('category_id', '{{products}}', 'category_id');
        $this->createIndex('url', '{{products}}', 'url', true);
        $this->createIndex('price', '{{products}}', 'price');
    }

    public function down()
    {
        $this->dropTable('{{products}}');
        return true;
    }

}