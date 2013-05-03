<?php

/**
 * Description of ProductRule
 *
 * @author shults
 */
class ProductRule extends CBaseUrlRule
{

    const RULE_ROUTE = 'catalog/product';

    public function createUrl($manager, $route, $params, $ampersand)
    {
        if ($route === self::RULE_ROUTE && $params['product_id']) {
            if (($product = Products::model()->findByPk($params['product_id']) ) !== null) {
                $url = isset($params['language']) ? $params['language'] . '/' : '';
                return $url . 'p' . $product->product_id . '-' . $product->url;
            }
        }
        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        
        if (preg_match('/([a-z]{2})?\/?catalog\/product(?:\/product_id\/(\d+))?/i', $pathInfo, $matches)) {
            $params = array();
            if ($matches[1])
                $params['language'] = $_GET['language'] = $matches[1];
            if ($matches[2]) {
                $params['product_id'] = $matches[2];
            } else if ($_GET['product_id']) {
                $params['product_id'] = $_GET['product_id'];
            }
            if ($url = $manager->createUrl(self::RULE_ROUTE, $params))
                $request->redirect($url);
        }
        
        if (preg_match('/^([a-z]{2})?\/?p(\d+)-([a-z0-9_-]+)$/i', $pathInfo, $matches)) {
            $languageCode = $matches[1];
            $product_id = $matches[2];
            $url = $matches[3];

            $_GET['product_id'] = $product_id;

            if ($languageCode != '')
                $_GET['language'] = $languageCode;

            if (($product = Products::model()->enabled()->findByPk($product_id)) === null)
                return false;

            if ($product->url != $url) {
                $request->redirect($this->createUrl($manager, self::RULE_ROUTE, array(
                            'product_id' => $product_id
                                ), '&'));
            }
            return self::RULE_ROUTE;
        }
        return false;
    }

}