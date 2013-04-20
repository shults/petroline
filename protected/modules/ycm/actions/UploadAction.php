<?php

/**
 * Description of UploadAction
 */
class UploadAction extends CAction
{

    public $path;
    public $publicPath;
    public $imagestore;
    public $folder;

    public function init()
    {
        $this->imagestore = Yii::app()->params->imagestore;

        if (!isset($this->folder)) {
            $this->folder = "tmp";
        }

        if (!isset($this->path)) {
            $this->path = realpath(Yii::app()->getBasePath()) . "/../{$this->imagestore}/{$this->folder}" . "/";
        }

        if (!isset($this->publicPath)) {
            $this->publicPath = Yii::app()->getBaseUrl() . "/{$this->imagestore}/{$this->folder}/";
        }
        $this->createFolder($this->path);
    }

    public function run()
    {
        $this->init();

        $this->sendHeaders();
        //Here we check if we are deleting and uploaded file
        $this->handleDeleting() or $this->handleUploading();
    }

    protected function sendHeaders()
    {
        //This is for IE which doens't handle 'Content-type: application/json' correctly
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
    }

    public function handleUploading()
    {
        Yii::import("xupload.models.XUploadForm");
        //Here we define the paths where the files will be stored temporarily
        $path = $this->path;
        $publicPath = $this->publicPath;

        $model = new XUploadForm;
        $model->file = CUploadedFile::getInstance($model, 'file');
        //We check that the file was successfully uploaded
        if ($model->file !== null) {
            //Grab some data
            $model->mime_type = $model->file->getType();
            $model->size = $model->file->getSize();
            $model->name = $model->file->getName();

            if ($model->validate()) {
                /* return array image property */
                $image_info = getimagesize($model->file->getTempName());
                $filepath = $this->generate_code() . ".jpg";
                //Move our file to our temporary dir
                $model->file->saveAs($path . $filepath);
                chmod($path . $filepath, 0777);

                //here you can also generate the image versions you need
                //using something like PHPThumb
                //Now we need to save this path to the user's session
                if (Yii::app()->user->hasState('images')) {
                    $images = Yii::app()->user->getState('images');
                } else {
                    $images = array();
                }
                $images[] = array(
                    'path' => $path . $filepath,
                    'filepath' => $filepath,
                    'folder' => $this->folder,
                );
                Yii::app()->user->setState('images', $images);

                //Save uploaded files to appropriate directory
                $image_id = $this->addImages();
                
                //Now we need to tell our widget that the upload was succesfull
                //We do so, using the json structure defined in
                // https://github.com/blueimp/jQuery-File-Upload/wiki/Setup

                echo json_encode(array(array(
                        "name" => $model->name,
                        "type" => $model->mime_type,
                        "size" => $model->size,
                        "url" => $publicPath . $filepath,
                        //"thumbnail_url" => $publicPath . $filepath,
                        "delete_url" => Yii::app()->createUrl("ycm/product/upload", array(
                            '_method' => "delete",
                            'file' => $filepath,
                            'filepath' => $filepath,
                            'image_id' => $image_id,
                            'folder' => $this->folder,
                        )),
                        "delete_type" => "POST"
                    )
                ));
            } else {
                //If the upload failed for some reason we log some data and let the widget know
                echo json_encode(array(
                    array("error" => $model->getErrors('file'),
                        )));
                Yii::log("XUploadAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction"
                );
            }
        } else {
            throw new CHttpException(500, "Could not upload file");
        }
    }

    public function addImages()
    {
        $image_id = false;
        $product_id = Yii::app()->request->getParam('product_id');
        //If we have pending images
        if (Yii::app()->user->hasState('images')) {
            $userImages = Yii::app()->user->getState('images');
            //Resolve the final path for our images
            $path = Yii::app()->getBasePath() . "/../$this->imagestore/{$this->folder}/";
            //Now lets create the corresponding models
            foreach ($userImages as $image) {
                if (is_file($image["path"])) {
                    $img = new ProductImages();
                    $img->product_id = $product_id;
                    $img->filepath = $image["filepath"];
                    $img->folder = $image["folder"];
                    
                    if (!$img->save()) {
                        //Its always good to log something
                        Yii::log("Could not save Image:\n" . CVarDumper::dumpAsString(
                                        $img->getErrors()), CLogger::LEVEL_ERROR);
                        //this exception will rollback the transaction
                        throw new Exception('Could not save Image');
                    }
                } else {
                    //You can also throw an execption here to rollback the transaction
                    Yii::log($image["path"] . " is not a file", CLogger::LEVEL_WARNING);
                }
            }
            //Clear the user's session
            Yii::app()->user->setState('images', null);
        }

        return $img->image_id;
    }

    protected function handleDeleting()
    {
        if (isset($_GET["_method"]) && $_GET["_method"] == "delete") {
            $filepath = Yii::app()->request->getParam('filepath');
            $image_id = Yii::app()->request->getParam('image_id');
            $folder = Yii::app()->request->getParam('folder');
            $this->deleteImage($filepath, $image_id, $folder);
            echo json_encode(true);
            return true;
        }
        return false;
    }

    public function deleteImage($_filepath, $_image_id, $_folder)
    {
        $image = ProductImages::model()->findByPk($_image_id);
        $path = realpath(Yii::app()->getBasePath() . "/../$this->imagestore/{$_folder}") . "/";
        $image->delete();
        if (is_file($path . $_filepath)) {
            unlink($path . $_filepath);
        } else {
            throw new Exception('No such file - '. $path . $_filepath);
        }
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
