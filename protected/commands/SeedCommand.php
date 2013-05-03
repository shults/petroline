<?php

Yii::import('system.test.CDbFixtureManager');

/**
 * Description of SeedCommand
 *
 * @author shults
 */
class SeedCommand extends CConsoleCommand
{

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
            $command->insert('{{products}}', array(
                'category_id' => $categoriesRow['category_id'],
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
        $transate = strtolower($this->getTranslitRus($transate));
        return trim(preg_replace('/[\s\#\$\,\/\`\—\-\.\\\'\+\(\)\\\\]+/i', '-', $transate));
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

}

