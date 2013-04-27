<?php

/**
 * Description of ErrorController
 *
 * @author shults
 */
class ErrorController extends CController
{
    public $layout = '//';

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

}
