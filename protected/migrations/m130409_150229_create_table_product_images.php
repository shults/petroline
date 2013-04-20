<?php

class m130409_150229_create_table_product_images extends CDbMigration
{

    public function up()
    {
        $this->createTable('{{product_images}}', array(
            'image_id' => 'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'product_id' => 'INT UNSIGNED NOT NULL',
            'filepath' => 'CHAR(24) NOT NULL',
            'folder' => 'CHAR(32) NOT NULL',
        ));
        $this->createIndex('product_id', '{{product_images}}', 'product_id');
    }

    public function down()
    {
        $this->dropTable('{{product_images}}');
        return true;
    }

}