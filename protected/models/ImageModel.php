<?php

/**
 * Description of ImageModel
 *
 * @author shults
 */
class ImageModel
{

    const PREVIEW = 75;
    const SMALL = 150;
    const NORMAL = 250;
    const BIG = 500;
    const NO_PHOTO = 'no-photo.jpg';
    const CACHE_DIR = 'cache';

    /** @var string|null if not defined */
    private static $_imageDir;

    /**
     * @return ImageModel
     */
    public static function model()
    {
        return new ImageModel;
    }

    /**
     * @return mixed
     */
    private static function imageDir()
    {
        if (self::$_imageDir === null) {
            self::$_imageDir = Yii::app()->params['imagestore'];
        }
        return self::$_imageDir;
    }

    /**
     * @return string
     */
    private function getNotPhotoPath()
    {
        return Yii::getPathOfAlias('application') . '/' . self::NO_PHOTO;
    }

    /**
     * @param $filename
     * @param $width
     * @param $height
     * @param $type
     * @return string
     */
    private static function getNewFileName($filename, $width, $height, $type)
    {
        $info = pathinfo($filename);

        $extension = $info['extension'];
        $newImage = self::imageDir() . '/' . self::CACHE_DIR . '/'
            . substr($filename, strlen(self::imageDir()) + 1, strpos($filename, '.') - (strlen(self::imageDir()) + 1))
            . '-' . $width . 'x' . $height . $type . '.' . $extension;

        return $newImage;
    }

    /**
     * @param string $filename
     * @param int $width
     * @param int $height
     * @param string $type
     * @return String or null if file not found
     */
    public function resize($filename, $width, $height, $type = "")
    {
        if (!file_exists($filename) || !is_file($filename)) {
            $filename = $this->getNotPhotoPath();
        }

        $oldImage = $filename;
        $newImage = self::getNewFileName($filename, $width, $height, $type);

        if (!file_exists($newImage) || (filemtime($oldImage) > filemtime($newImage))) {

            if (!file_exists($dirnname = dirname(str_replace('../', '', $newImage)))) {
                mkdir($dirnname, 0755, true);
            }

            list($originalWidth, $originalHeight) = getimagesize($oldImage);

            if ($originalWidth != $width || $originalHeight != $height) {
                $image = new Image($oldImage);
                $image->resize($width, $height, $type);
                $image->save($newImage);
            } else {
                copy($oldImage, $newImage);
            }
        }

        return Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $newImage;
    }

    /**
     * @param $filename
     * @return String
     */
    public function preview($filename)
    {
        return $this->resize($filename, self::PREVIEW, self::PREVIEW);
    }

    /**
     * @param $filename
     * @return String
     */
    public function small($filename)
    {
        return $this->resize($filename, self::SMALL, self::SMALL);
    }

    /**
     * @param $filename
     * @return String
     */
    public function normal($filename)
    {
        return $this->resize($filename, self::NORMAL, self::NORMAL);
    }

    /**
     * @param $filename
     * @return String
     */
    public function big($filename)
    {
        return $this->resize($filename, self::BIG, self::BIG);
    }

}