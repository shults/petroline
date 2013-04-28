<?php

/**
 * Description of CategoryRule
 *
 * @author shults
 */
class CategoryRule extends CBaseUrlRule
{

    const RULE_ROUTE = 'catalog/category';

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === self::RULE_ROUTE && $params['category_id']) {
            if (($category = Categories::model()->findByPk($params['category_id'])) !== null) {
                $url = isset($params['language']) ? $params['language'] . '/' : '';
                return $url . 'c' . $category->category_id . '-' . $category->url;
            }
        }
        return false;
    }

    /**
     * 
     * @param UrlManager $manager
     * @param CHttpRequest $request
     * @param String $pathInfo
     * @param String $rawPathInfo
     * @return boolean
     */
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        // запрет прямого доступа к контроллеру
        if (preg_match('/([a-z]{2})?\/?catalog\/category(?:\/category_id\/(\d+))?/i', $pathInfo, $matches)) {
            $params = array();
            if ($matches[1])
                $params['language'] = $_GET['language'] = $matches[1];
            if ($matches[2])
                $params['category_id'] = $_GET['category_id'] = $matches[2];
            if ($url = $manager->createUrl(self::RULE_ROUTE, $params))
                $request->redirect($url);
        }
        if (preg_match('/([a-z]{2})?\/?c(\d+)-([a-z0-9_-]+)/i', $pathInfo, $matches)) {
            $languageCode = $matches[1];
            $category_id = $matches[2];
            $url = $matches[3];
            $_GET['category_id'] = $category_id;
            if ($languageCode != '')
                $_GET['language'] = $languageCode;
            if (($category = Categories::model()->findByPk($category_id)) === null)
                return false;
            if ($category->url != $url) {
                $request->redirect($this->createUrl($manager, self::RULE_ROUTE, array(
                            'category_id' => $category_id
                                ), '&'));
            }
            return self::RULE_ROUTE;
        }
        return false;
    }

}