<?php

class FileBehavior extends CModelBehavior
{
    /**
     * Get relative file URL.
     *
     * @param string $attribute
     * @return string the file URL
     */
    public function getFileUrl($attribute)
    {
        $name = get_class($this->owner);
        if ($this->owner->hasAttribute($attribute) && !empty($this->owner->$attribute)) {
            $file = $this->owner->$attribute;
            $uploadUrl = Yii::app()->modules['ycm']['uploadUrl'] ? Yii::app()->modules['ycm']['uploadUrl'] : Yii::app()->request->baseUrl . '/uploads';
            return $uploadUrl . '/' . strtolower($name) . '/' . strtolower($attribute) . '/' . $file;
        }
        return false;
    }

    /**
     * Get absolute file URL.
     *
     * @param string $attribute
     * @return string the absolute file URL
     */
    public function getAbsoluteFileUrl($attribute)
    {
        $url = $this->getFileUrl($attribute);
        if ($url) {
            return Yii::app()->getRequest()->getHostInfo() . $url;
        }
        return false;
    }

    /**
     * Get the relative filePath
     * 
     * @param type $attribute
     * @return String or null if file not found
     */
    public function getFilePath($attribute)
    {
        $modelName = strtolower(get_class($this->owner));
        if ($this->owner->hasAttribute($attribute) && !empty($this->owner->$attribute)) {
            $uploadPath = Yii::app()->params['imagestore'] ? Yii::app()->params['imagestore'] : 'uploads';
            $filePath = $uploadPath . DIRECTORY_SEPARATOR . $modelName . DIRECTORY_SEPARATOR
                    . strtolower($attribute) . DIRECTORY_SEPARATOR . $this->owner->$attribute;
            return $filePath;
        }
        return null;
    }

    public function getAbsoluteFilePath($attribute)
    {
        if (($filePath = $this->getFilePath($attribute)) !== null) {
            return realpath(Yii::getPathOfAlias('root')) . DIRECTORY_SEPARATOR . $filePath;
        }
        return null;
    }
}