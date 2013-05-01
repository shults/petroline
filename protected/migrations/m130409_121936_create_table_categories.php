<?php

class m130409_121936_create_table_categories extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{categories}}', array(
            'category_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'parent_category_id' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
            'order' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 1',
            'title' => 'VARCHAR(100) NOT NULL',
            'url' => 'VARCHAR(100) NOT NULL',
            'status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'filename' => 'VARCHAR(100)',
            'meta_title' => 'VARCHAR(100)',
            'meta_description' => 'TEXT',
            'meta_keywords' => 'TEXT',
            'description' => 'TEXT',
            'prom_id' => 'INT UNSIGNED',
            'prom_parent_id' => 'SMALLINT UNSIGNED',
        ));
        $this->createIndex('language_id', '{{categories}}', 'language_id');
        $this->createIndex('order', '{{categories}}', 'order');
        $this->createIndex('language_id;parent_category_id;url', '{{categories}}', 'language_id, parent_category_id, url', true);
        $this->createIndex('language_id;prom_id', '{{categories}}', 'language_id, prom_id');
        $this->addForeignKey('categories_language_id_fk', '{{categories}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{categories}}');
        return true;
    }

}