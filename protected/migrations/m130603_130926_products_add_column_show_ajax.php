<?php

class m130603_130926_products_add_column_show_ajax extends CDbMigration
{
	public function up()
	{
        $this->addColumn('{{products}}', 'display_ajax', 'BOOLEAN NOT NULL DEFAULT 1');
	}

	public function down()
	{
		$this->dropColumn('{{products}}', 'display_ajax');
		return true;
	}
}