<?php

Yii::import('system.test.CDbFixtureManager');

/**
 * Description of SeedCommand
 *
 * @author shults
 */
class SeedCommand extends CConsoleCommand
{

    const YANDEX_TRANSLATE_API_KEY = 'trnsl.1.1.20130503T152435Z.e6b955314e9738d7.6ba88118bb23318727952203f5319ce55bc11181';

    private $defaultTables = array(
        '{{languages}}',
        '{{users}}',
        '{{config}}',
        '{{deliveries}}',
        '{{payments}}',
    );
    private $afterImportSeed = array(
        '{{new_products}}'
    );

    /**
     * This method inserts default test data
     */
    public function actionIndex()
    {
        $fixtureManager = new CDbFixtureManager();
        $fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
        $fixtureManager->prepare();
    }

    /**
     * This method make import from csv file
     * 
     * @param String $csv path to export csv file
     */
    public function actionImport($csv, $withImages = false)
    {
        $currentDir = getcwd();
        $csvFilePath = $currentDir . DIRECTORY_SEPARATOR . $csv;
        if (!file_exists($csvFilePath)) {
            $this->usageError('File ' . $csvFilePath . ' does not exists');
        }
        $this->truncateAllTables();
        $this->inserDefaultData();
        $this->clearUploadDir();
        $this->importCategories();
        /**
         * Код_товара	
         * Название_позиции	
         * Ключевые_слова	
         * Краткое_описание	
         * Тип_товара	
         * Цена	
         * Валюта	
         * Единица_измерения	
         * Минимальный_объем_заказа	
         * Оптовая_цена	
         * Минимальный_заказ_опт	
         * Ссылка_изображения	
         * Наличие	
         * Номер_группы	
         * Детальное_описание	
         * Адрес_подраздела	
         * Возможность_поставки	
         * Срок_поставки	
         * Способ_упаковки	
         * Уникальный_идентификатор	
         * Идентификатор_товара	
         * Идентификатор_подраздела	
         * Идентификатор_группы	
         * Производитель	
         * Страна производитель
         */
        $csvFile = fopen($csvFilePath, 'r');
        echo "Start product import ...\n";
        $command = Yii::app()->db->createCommand();
        $productNumber = 0;
        while ($data = fgetcsv($csvFile, 5000, ",")) {
            if ($productNumber == 0) {
                $productNumber++;
                continue;
            }
            $productUrl = $this->getTranslitUrlRus($data[1]);
            if ($dublicateItem = Yii::app()->db->createCommand('SELECT * FROM {{products}} WHERE url=\'' . $productUrl . '\'')->queryRow())
                $productUrl .= '-prom-id-' . $data[19];

            if (!$categoriesRow = Yii::app()->db->createCommand('SELECT * FROM {{categories}} WHERE `prom_id`=' . (int) $data[13] . ' AND `language_id`=1')->queryRow()) {
                $this->usageError("Category with prom_id=" . $data[13] . " not found");
            }

            if ($orderQuery = Yii::app()->db->createCommand('SELECT MAX(`order`) AS `order` FROM {{products}} WHERE `category_id`=' . (int) $categoriesRow['category_id'])->queryRow()) {
                $order = $orderQuery['order'] ? $orderQuery['order'] + 1 : 1;
            } else {
                $order = 1;
            }

            $command->insert('{{products}}', array(
                'category_id' => $categoriesRow['category_id'],
                'order' => $order,
                'language_id' => 1,
                'url' => $productUrl,
                'title' => $data[1],
                'description' => $data[14],
                'price' => $data[5],
                'trade_price' => $data[9],
                'min_trade_order' => $data[10],
                'meta_title' => $data[1],
                'meta_description' => $data[3],
                'meta_keywords' => $data[2],
                'created_at' => new CDbExpression('NOW()'),
                'updated_at' => new CDbExpression('NOW()'),
                'created_by' => 1,
                'updated_by' => 1,
            ));
            $product_id = Yii::app()->db->getLastInsertID();
            $imagestore = 'uploads';
            $folderPath = realpath(Yii::getPathOfAlias('root')) . DIRECTORY_SEPARATOR . 'uploads'
                    . DIRECTORY_SEPARATOR . 'productimages' . DIRECTORY_SEPARATOR . 'filepath';
            $this->createFolder($folderPath);
            $file = $data[11];
            if ($withImages) {
                $files = explode(',', $file);
                foreach ($files as $file) {
                    if ($file && $content = file_get_contents(trim($file))) {
                        $imageFileName = $this->getHash() . $this->getExtension($file);
                        $newfile = $folderPath . DIRECTORY_SEPARATOR . $imageFileName;
                        $imageFile = fopen($newfile, "w");
                        fwrite($imageFile, $content);
                        fclose($imageFile);
                        $command->insert('{{product_images}}', array(
                            'product_id' => $product_id,
                            'filepath' => $imageFileName,
                        ));
                    }
                }
            }
            echo "Product #{$productNumber} imported\n";
            $productNumber++;
        }
        fclose($csvFile);

        //// Ukrainian
        $csvFile = fopen($csvFilePath, 'r');
        echo "Start product import ...\n";
        $command = Yii::app()->db->createCommand();
        $productNumber = 0;
        while ($data = fgetcsv($csvFile, 5000, ",")) {
            if ($productNumber == 0) {
                $productNumber++;
                continue;
            }
            $productUrl = $this->getTranslitUrlRus($data[1]);
            if ($dublicateItem = Yii::app()->db->createCommand('SELECT * FROM {{products}} WHERE url=\'' . $productUrl . '\'')->queryRow())
                $productUrl .= '-prom-id-' . $data[19];

            if (!$categoriesRow = Yii::app()->db->createCommand('SELECT * FROM {{categories}} WHERE `prom_id`=' . (int) $data[13] . ' AND `language_id`=2')->queryRow()) {
                $this->usageError("Category with prom_id=" . $data[13] . " not found");
            }

            if ($orderQuery = Yii::app()->db->createCommand('SELECT MAX(`order`) AS `order` FROM {{products}} WHERE `category_id`=' . (int) $categoriesRow['category_id'])->queryRow()) {
                $order = $orderQuery['order'] ? $orderQuery['order'] + 1 : 1;
            } else {
                $order = 1;
            }

            $command->insert('{{products}}', array(
                'category_id' => $categoriesRow['category_id'],
                'order' => $order,
                'language_id' => 2,
                'url' => $productUrl,
                'title' => $this->translate($data[1]),
                'description' => $this->translate($data[14], self::FORMAT_HTML),
                'price' => $data[5],
                'trade_price' => $data[9],
                'min_trade_order' => $data[10],
                'meta_title' => $this->translate($data[1]),
                'meta_description' => $this->translate($data[3]),
                'meta_keywords' => $this->translate($data[2]),
                'created_at' => new CDbExpression('NOW()'),
                'updated_at' => new CDbExpression('NOW()'),
                'created_by' => 1,
                'updated_by' => 1,
            ));
            $product_id = Yii::app()->db->getLastInsertID();
            $imagestore = 'uploads';
            $folderPath = realpath(Yii::getPathOfAlias('root')) . DIRECTORY_SEPARATOR . 'uploads'
                    . DIRECTORY_SEPARATOR . 'productimages' . DIRECTORY_SEPARATOR . 'filepath';
            $this->createFolder($folderPath);
            $file = $data[11];
            if ($withImages) {
                $files = explode(',', $file);
                foreach ($files as $file) {
                    if ($file && $content = file_get_contents(trim($file))) {
                        $imageFileName = $this->getHash() . $this->getExtension($file);
                        $newfile = $folderPath . DIRECTORY_SEPARATOR . $imageFileName;
                        $imageFile = fopen($newfile, "w");
                        fwrite($imageFile, $content);
                        fclose($imageFile);
                        $command->insert('{{product_images}}', array(
                            'product_id' => $product_id,
                            'filepath' => $imageFileName,
                        ));
                    }
                }
            }
            echo "Product #{$productNumber} imported\n";
            $productNumber++;
        }
        fclose($csvFile);

        $this->afterImportSeed();
        echo "Import finished\n";
    }

    private function createFolder($path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        if (!is_writable($path)) {
            chmod($path, 0777);
        }
    }

    /**
     * Returns time-based hash code
     * sha1 algorithm
     * 
     * @param String $length
     * @return String
     */
    private function getHash($salt = null)
    {
        if ($salt === null)
            return sha1(microtime(true));
        else
            return sha1(microtime(true) . $salt);
    }

    /**
     * Returns extension of file
     * 
     * @param String $fileName
     * @param boolean $withDot Description
     * @return String file extension
     */
    private function getExtension($fileName, $withDot = true)
    {
        $extension = CFileHelper::getExtension($fileName);
        if ($extension === 'tiff' || $extension === 'bmp') {
            $extension = 'jpg';
        }
        return $withDot ? '.' . $extension : $extension;
    }

    /**
     * This method translates from russin to english
     * 
     * @param String $transate
     * @return String
     */
    private function getTranslitRus($transate)
    {
        $rus_alphabet = array(
            'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й',
            'К', 'Л', 'М', 'Н', 'О', 'П', 'Р', 'С', 'Т', 'У', 'Ф',
            'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я',
            'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й',
            'к', 'л', 'м', 'н', 'о', 'п', 'р', 'с', 'т', 'у', 'ф',
            'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я'
        );

        $rus_alphabet_translit = array(
            'A', 'B', 'V', 'G', 'D', 'E', 'IO', 'ZH', 'Z', 'I', 'I',
            'K', 'L', 'M', 'N', 'O', 'P', 'R', 'S', 'T', 'U', 'F',
            'H', 'C', 'CH', 'SH', 'SH', '', 'Y', '', 'E', 'IU', 'IA',
            'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'i',
            'k', 'l', 'm', 'n', 'o', 'p', 'r', 's', 't', 'u', 'f',
            'h', 'c', 'ch', 'sh', 'sh', '', 'y', '', 'e', 'iu', 'ia'
        );

        return str_replace($rus_alphabet, $rus_alphabet_translit, $transate);
    }

    private function getTranslitUrlRus($transate)
    {
        $url = strtolower($this->getTranslitRus($transate));
        $url = trim(preg_replace('/[\s\#\$\,\/\`\—\-\.\\\'\+\(\)\\\\\:\%]+/i', '-', $url));
        $url = preg_replace('/\-[\-]+/i', '-', $url);
        $url = preg_replace('/\-$/i', '', $url);
        $url = preg_replace('/^\-/i', '', $url);
        return $url;
    }

    /**
     * This method truncate all table
     */
    private function truncateAllTables()
    {
        $tables = Yii::app()->db->getSchema()->getTableNames();
        $fixtureManager = new CDbFixtureManager();
        foreach ($tables as $tableName) {
            if ($tableName === 'tbl_migration')
                continue;
            $fixtureManager->resetTable($tableName);
        }
    }

    /**
     * This method insserts default data into table
     */
    private function inserDefaultData()
    {
        $fixtureManager = new CDbFixtureManager();
        $fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
        foreach ($this->defaultTables as $tableName) {
            echo Yii::t('console_seed', "Inserting data into table \"{tableName}\" ...\n", array(
                '{tableName}' => Yii::app()->db->getSchema()->getTable($tableName)->name,
            ));
            $fixtureManager->resetTable($tableName);
            $fixtureManager->loadFixture($tableName);
        }
    }

    private function afterImportSeed()
    {
        $fixtureManager = new CDbFixtureManager();
        $fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
        foreach ($this->afterImportSeed as $tableName) {
            echo Yii::t('console_seed', "Inserting data into table \"{tableName}\" ...\n", array(
                '{tableName}' => Yii::app()->db->getSchema()->getTable($tableName)->name,
            ));
            $fixtureManager->resetTable($tableName);
            $fixtureManager->loadFixture($tableName);
        }
    }

    private function clearUploadDir()
    {
        echo "Deleting product images...\n";
        $uploadDir = realpath(Yii::getPathOfAlias('root')) . DIRECTORY_SEPARATOR . 'uploads'
                . DIRECTORY_SEPARATOR . 'productimages';
        system('rm -rf ' . $uploadDir);
        echo "Images deleted ...\n";
    }

    private function importCategories()
    {
        echo "Begin categories import...\n";
        $fixtureManager = new CDbFixtureManager();
        $fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
        $fixtureManager->loadFixture('{{categories}}');
        echo "Endcategories import...\n";
    }

    const FORMAT_PLAIN = 'plain';
    const FORMAT_HTML = 'html';

    private function translate($text, $format = self::FORMAT_PLAIN, $from = 'ru', $to = 'uk')
    {
        /*
         * https://translate.yandex.net/api/v1.5/tr.json/translate?key=API-ключ&lang=en-ru&text=To+be,+or+not+to+be%3F&text=That+is+the+question.
         */
        $ch = curl_init('https://translate.yandex.net/api/v1.5/tr.json/translate');
        curl_setopt_array($ch, array(
            CURLOPT_AUTOREFERER => 1,
            CURLOPT_POST => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POSTFIELDS => array(
                'key' => self::YANDEX_TRANSLATE_API_KEY,
                'lang' => $from . '-' . $to,
                'format' => $format,
                'text' => $text
            )
        ));
        $data = json_decode(curl_exec($ch));
        curl_close($ch);
        return $data->text[0];
    }
    
    public function actionTest()
    {
        $dataItems = array(
            'Для POST- запросов максимальный размер передаваемого текста составляет 10000 символов',
            'В GET-запросах ограничивается не размер передаваемого текста, а размер всей строки запроса, которая кроме текста может содержать и другие параметры. Максимальный размер строки запроса - 10Кб.'
        );
        foreach ($dataItems as $item) {
            $this->translate($item);
        }
    }

}

