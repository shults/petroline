<?php

class m130409_121936_create_table_categories extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{categories}}', array(
            'category_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'language_id' => 'SMALLINT UNSIGNED NOT NULL',
            'parent_category_id' => 'SMALLINT UNSIGNED NOT NULL DEFAULT 0',
            'title' => 'VARCHAR(100) NOT NULL',
            'url' => 'VARCHAR(100) NOT NULL',
            'status' => 'BOOLEAN NOT NULL DEFAULT 1',
            'filename' => 'VARCHAR(100)',
            'meta_title' => 'VARCHAR(100)',
            'meta_description' => 'TEXT',
            'meta_keywords' => 'TEXT',
            'description' => 'TEXT',
        ));
        $this->createIndex('language_id', '{{categories}}', 'language_id');
        $this->createIndex('language_id_url', '{{categories}}', 'language_id,url', true);
        $this->addForeignKey('categories_language_id_fk', '{{categories}}', 'language_id', '{{languages}}', 'language_id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{categories}}');
        return true;
    }

}