<?php

class m130409_121936_create_table_categories extends CDbMigration
{
	public function up()
    {
        $this->createTable('{{categories}}', array(
            'category_id' => 'SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
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
    }

    public function down()
    {
        $this->dropTable('{{categories}}');
        return true;
    }
}