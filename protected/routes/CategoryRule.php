<?php

/**
 * Description of CategoryRule
 *
 * @author shults
 */
class CategoryRule extends CBaseUrlRule
{

    public function createUrl($manager, $route, $params, $ampersand)
    {
        /*if ($route === 'catalog/category' && $params['category_id']) {
            if (($category = Categories::model()->findByPk($params['category_id'])) !== null) {
                $url = isset($params['language']) ? $params['language'] . '/' : '';
                return $url . 'c' . $category->category_id . '-' . $category->url;
            }
        }*/
        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        /*echo '<pre>';
        print_r($manager);
        echo '</pre>';
        die;*/
        return false;
    }

}