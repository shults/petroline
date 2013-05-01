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

    private $_imageDir;
    private $_cacheDir = 'cache';

    /**
     * 
     * @return ImageModel
     */
    public static function model()
    {
        return new ImageModel;
    }

    public function __construct()
    {
        $this->_imageDir = Yii::app()->params['imagestore'];
    }

    /**
     * 
     * @param type $filename
     * @param type $width
     * @param type $height
     * @param type $type
     * @return String or null if file not found
     */
    public function resize($filename, $width, $height, $type = "")
    {

        if (!file_exists($filename) || !is_file($filename)) {
            $filename = $this->_imageDir . '/' . self::NO_PHOTO;
        }

        $info = pathinfo($filename);

        $extension = $info['extension'];
        $old_image = $filename;
        $new_image = $this->_imageDir . '/' . $this->_cacheDir . '/'
                . substr($filename, strlen($this->_imageDir) + 1, strpos($filename, '.') - (strlen($this->_imageDir) + 1))
                . '-' . $width . 'x' . $height . $type . '.' . $extension;

        if (!file_exists($new_image) || (filemtime($old_image) > filemtime($new_image))) {
            $path = '';

            if (!file_exists($dirnname = dirname(str_replace('../', '', $new_image)))) {
                mkdir($dirnname, 0777, true);
            }

            list($width_orig, $height_orig) = getimagesize($old_image);

            if ($width_orig != $width || $height_orig != $height) {
                $image = new Image($old_image);
                $image->resize($width, $height, $type);
                $image->save($new_image);
            } else {
                copy($old_image, $new_image);
            }
        }

        return Yii::app()->getBaseUrl(true) . DIRECTORY_SEPARATOR . $new_image;
    }

    public function preview($filename)
    {
        return $this->resize($filename, self::PREVIEW, self::PREVIEW);
    }

    public function small($filename)
    {
        return $this->resize($filename, self::SMALL, self::SMALL);
    }

    public function normal($filename)
    {
        return $this->resize($filename, self::NORMAL, self::NORMAL);
    }

    public function big($filename)
    {
        return $this->resize($filename, self::BIG, self::BIG);
    }

}