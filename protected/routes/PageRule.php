<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PageRule
 *
 * @author shults
 */
class PageRule extends CBaseUrlRule
{

    const RULE_ROUTE = 'page/view';

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === self::RULE_ROUTE && $params['page_id']) {
            if (($page = Page::model()->findByPk($params['page_id']) ) !== null) {
                $url = isset($params['language']) ? $params['language'] . '/' : '';
                return $url . $page->url;
            }
        }
        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {

        if (preg_match('/([a-z]{2})?\/?page\/view(?:\/page_id\/(\d+))?/i', $pathInfo, $matches)) {
            $params = array();
            if ($matches[1])
                $params['language'] = $_GET['language'] = $matches[1];
            if ($matches[2]) {
                $params['page_id'] = $matches[2];
            } else if ($_GET['page_id']) {
                $params['page_id'] = $_GET['page_id'];
            }
            if ($url = $manager->createUrl(self::RULE_ROUTE, $params))
                $request->redirect($url, true, 301);
        }

        if (preg_match('/^(?:([a-z]{2})\/)?([a-z0-9_-]+)$/i', $pathInfo, $matches)) {
            $languageCode = $matches[1];
            $page_url = $matches[2];

            if ($languageCode != '')
                $_GET['language'] = $languageCode;

            if (($page = Page::model()->enabled()->find('url=:url', array(':url' => $page_url))) === null) {
                return false;
            }

            $_GET['page'] = $page;
            return self::RULE_ROUTE;
        }
        return false;
    }

}

?>
