<?php

class m130528_102222_add_config__seo_records extends CDbMigration
{

    public function up()
    {
        $dataItems = array(
            array(
                'language_id' => 1,
                'key' => 'about_us_meta_description',
                'title' => 'Описание meta (Страница о нас)',
                'value' => 'Описание meta (Страница о нас)'
            ),
            array(
                'language_id' => 2,
                'key' => 'about_us_meta_description',
                'title' => 'Опис meta (Сторінка о нас)',
                'value' => 'Опис meta (Сторінка о нас)'
            ),
            array(
                'language_id' => 1,
                'key' => 'contacts_meta_description',
                'title' => 'Описание meta (Страница контакты)',
                'value' => 'Описание meta (Страница контакты)'
            ),
            array(
                'language_id' => 2,
                'key' => 'contacts_meta_description',
                'title' => 'Опис meta (Сторінка контакти)',
                'value' => 'Опис meta (Сторінка контакти)'
            ),
            array(
                'language_id' => 1,
                'key' => 'delivery_payment_meta_description',
                'title' => 'Описание meta (Страница оплата и доставка)',
                'value' => 'Описание meta (Страница оплата и доставка)'
            ),
            array(
                'language_id' => 2,
                'key' => 'delivery_payment_meta_description',
                'title' => 'Опис meta (Сторінка оплата та доставка)',
                'value' => 'Опис meta (Сторінка оплата та доставка)'
            ),
            array(
                'language_id' => 1,
                'key' => 'main_page_content',
                'widget' => 'tinymce',
                'title' => 'Текст на главной',
                'value' => '<h2>Текст на главной</h2>'
            ),
            array(
                'language_id' => 2,
                'key' => 'main_page_content',
                'widget' => 'tinymce',
                'title' => 'Текст на головній',
                'value' => '<h1>Текст на головній</h1>'
            ),
        );
        foreach ($dataItems as $item) {
            $this->insert('{{config}}', $item);
        }
    }

    public function down()
    {
        echo "m130528_102222_add_config__seo_records does not support migration down.\n";
        return false;
    }

}