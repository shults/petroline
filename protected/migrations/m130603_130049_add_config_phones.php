<?php

class m130603_130049_add_config_phones extends CDbMigration
{

    public function up()
    {
        $this->insert("{{config}}", array(
            'language_id' => 1,
            'key' => 'contacts_header',
            'title' => 'Контакты (шапка)',
            'value' => '+380 (44) 200-22-55 <br/> +380 (67) 548-85-85 <br/> +380 (99) 905-59-99 <br/>Email:  2002255@ukr.net'
        ));
        
        $this->insert('{{config}}', array(
            'language_id' => 2,
            'key' => 'contacts_header',
            'title' => 'Контакти (шапка)',
            'value' => '+380 (44) 200-22-55 <br/> +380 (67) 548-85-85 <br/> +380 (99) 905-59-99 <br/>Email:  2002255@ukr.net'
        ));
    }

    public function down()
    {
        echo "m130603_130049_add_config_phones does not support migration down.\n";
        return false;
    }

}