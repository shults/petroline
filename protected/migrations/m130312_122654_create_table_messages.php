<?php

class m130312_122654_create_table_messages extends CDbMigration
{

	public function up()
	{
		$this->createTable('{{messages}}', array(
			'message_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
			'checked' => 'BOOLEAN NOT NULL DEFAULT false',
			'author_name' => 'VARCHAR(100) NOT NULL',
			'author_email' => 'VARCHAR(100) NOT NULL',
			'text' => 'TEXT NOT NULL',
			'sended_at' => 'DATETIME NOT NULL',
		));
	}

	public function down()
	{
		$this->dropTable('{{messages}}');
		return true;
	}

}