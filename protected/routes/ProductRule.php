<?php

/**
 * Description of ProductRule
 *
 * @author shults
 */
class ProductRule extends CBaseUrlRule
{

    public function createUrl($manager, $route, $params, $ampersand)
    {
        die($route);
        return false;
    }

    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
    {
        return false;
    }

}