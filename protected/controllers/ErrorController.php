<?php

/**
 * Description of ErrorController
 *
 * @author shults
 */
class ErrorController extends CController
{
    public $layout = '//';
    private $mainMenuItems;

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
    
    public function getMainMenuItems()
    {
        if ($this->mainMenuItems === null) {
            $this->mainMenuItems = array();
            $requestUri = Yii::app()->request->requestUri;
            $menuItems = require(Yii::getPathOfAlias('application.config') . '/front_main_menu_items.php');
            foreach ($menuItems as $item) {
                $this->mainMenuItems[] = array(
                    'label' => $item['label'],
                    'url' => $url = CHtml::normalizeUrl($item['url']),
                    //'class' => ($requestUri === $url) ? 'class="active"' : null,
                );
            }
        }
        return $this->mainMenuItems;
    }

}
