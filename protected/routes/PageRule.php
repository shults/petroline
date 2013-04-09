<?php

/**
 * Description of PageRule
 *
 * @author shults
 */
class PageRule extends CBaseUrlRule
{

	/**
	 * 
	 * @param CUrlManager $manager
	 * @param CHttpRequest $request
	 * @param string $pathInfo
	 * @param string $rawPathInfo
	 */
	public function parseUrl($manager, $request, $pathInfo, $rawPathInfo)
	{
		if ($page = Page::model()->find('url=:url', array(':url' => $pathInfo))) {
			$_GET['page_id'] = $page->page_id;
			return 'site/page';
		}
		return false;
	}

	public function createUrl($manager, $route, $params, $ampersand)
	{
		return false;
	}

}
