<?php

/**
 * Description of UrlManager
 *
 * @author shults
 */
class UrlManager extends CUrlManager
{

    public function createUrl($route, $params = array(), $ampersand = '&')
    {
        if (($langPrefix = Yii::app()->lang->getLangPrefix()) !== null)
            $params['language'] = $langPrefix;
        return parent::createUrl($route, $params, $ampersand);
    }

}
