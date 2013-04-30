<?php

class m130409_145346_create_table_products extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{products}}', array(
            'product_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'category_id' => 'SMALLINT UNSIGNED NOT NULL',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'url' => 'VARCHAR(150) NOT NULL',
            'title' => 'VARCHAR(100) NOT NULL',
            'status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'store_status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'description' => 'TEXT NOT NULL',
            'price' => 'DECIMAL(8,2)',
            'trade_price' => 'DECIMAL(8,2)',
            'min_trade_order' => 'SMALLINT UNSIGNED',
            'meta_title' => 'VARCHAR(100)',
            'meta_description' => 'TEXT',
            'meta_keywords' => 'TEXT',
            'created_at' => 'DATETIME',
            'updated_at' => 'DATETIME',
            'created_by' => 'INT UNSIGNED',
            'updated_by' => 'INT UNSIGNED',
        ));
        $this->createIndex('category_id', '{{products}}', 'category_id');
        $this->createIndex('language_id_url', '{{products}}', 'language_id, url', true);
        $this->createIndex('price', '{{products}}', 'price');
        $this->createIndex('language_id', '{{products}}', 'language_id');
        $this->addForeignKey('products_created_by_fk', '{{products}}', 'created_by', '{{users}}', 'user_id', 'SET NULL', 'SET NULL');
        $this->addForeignKey('products_updated_by_fk', '{{products}}', 'updated_by', '{{users}}', 'user_id', 'SET NULL', 'SET NULL');
    }

    public function down()
    {
        $this->dropTable('{{products}}');
        return true;
    }

}