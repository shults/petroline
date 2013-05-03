<?php

// controller to host connector action
class ElfinderController extends AdminController
{

    public function actions()
    {
        return array(
            'connector' => array(
                'class' => 'ext.elFinder.ElFinderConnectorAction',
                'settings' => array(
                    'root' => Yii::getPathOfAlias('webroot') . '/uploads/tinymce/',
                    'URL' => Yii::app()->baseUrl . '/uploads/tinymce/',
                    'rootAlias' => 'Home',
                    'mimeDetect' => 'none'
                )
            ),
        );
    }

}