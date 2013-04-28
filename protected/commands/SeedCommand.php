<?php

/**
 * Description of SeedCommand
 *
 * @author shults
 */
class SeedCommand extends CConsoleCommand
{

    public function actionIndex()
    {
        Yii::import('system.test.CDbFixtureManager');
        $fixtureManager = new CDbFixtureManager();
        $fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
        $fixtureManager->prepare();
    }

    public function actionImport()
    {
        //File to be opened
        $file = Yii::app()->basePath . '/commands/export_products.csv';
        //Open file (DON'T USE a+ pointer will be wrong!)
        $fp = fopen($file, 'r');
        //header("Content-Type: text/html;charset=utf-8");
        /**
         * Код_товара	Название_позиции	Ключевые_слова	Краткое_описание	Тип_товара	Цена	Валюта	Единица_измерения	Минимальный_объем_заказа	Оптовая_цена	Минимальный_заказ_опт	Ссылка_изображения	Наличие	Номер_группы	Детальное_описание	Адрес_подраздела	Возможность_поставки	Срок_поставки	Способ_упаковки	Уникальный_идентификатор	Идентификатор_товара	Идентификатор_подраздела	Идентификатор_группы	Производитель	Страна производитель

         */
        $i = 0;
        echo "Begin importing products ...\n";
        $command = Yii::app()->db->createCommand();


        while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
            $command->insert('{{products}}', array(
                'category_id' => $data[22],
                'language_id' => 1,
                'url' => 'Url' . $i,
                'title' => $data[1],
                'description' => $data[14],
                'price' => $data[5],
                'trade_price' => $data[9],
                'min_trade_order' => $data[10],
                'meta_title' => $data[1],
                'meta_description' => $data[3],
                'meta_keywords' => $data[2],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'created_by' => 1,
                'updated_by' => 1,
            ));
            $id = Yii::app()->db->getLastInsertID();

            //$folder = md5(Yii::app()->params->salt . $id);
            $folder = md5('[$8?k~Mfd=%' . $id);
            //$imagestore = Yii::app()->params->imagestore;
            $imagestore = 'uploads';
            $path = realpath(Yii::app()->getBasePath()) . "/../{$imagestore}/{$folder}" . "/";
            $this->createFolder($path);
            $file = $data[11];
            if ($i > 0) {
                $files = explode(',', $file);
                foreach ($files as $file) {
                    if ($file != '') { //if not an empty string
                        $content = file_get_contents(trim($file));
                        if ($content) {
                            $filepath = $this->generate_code() . ".jpg";
                            $newfile = $path . $filepath;

                            $fp2 = fopen($newfile, "w");
                            fwrite($fp2, $content);
                            fclose($fp2);
                            echo "Image saved\r\n";
                            $command->insert('{{product_images}}', array(
                                'product_id' => $id,
                                'filepath' => $filepath,
                                'folder' => $folder,
                            ));
                        } else {
                            echo "Image not opened!!! \r\n";
                        }
                    }
                }
            }
            $i++;
            echo "Product #{$i} imported\n";
        }
        fclose($fp);
        echo "END importing products!!!\n";
    }

    public function createFolder($path)
    {
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
            chmod($path, 0777);
            //throw new CHttpException(500, "{$this->path} does not exists.");
        } else if (!is_writable($path)) {
            chmod($path, 0777);
            //throw new CHttpException(500, "{$this->path} is not writable.");
        }
    }

    public function generate_code($length = 20)
    {
        $num = range(0, 9);
        $alf = range('a', 'z');
        $_alf = range('A', 'Z');
        $symbols = array_merge($num, $alf, $_alf);
        shuffle($symbols);
        $code_array = array_slice($symbols, 0, (int) $length);
        $code = implode("", $code_array);
        return $code;
    }

}

