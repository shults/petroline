<?php

/**
 * Description of PageController
 *
 * @author shults
 */
class PageController extends FrontController
{

    public function actionView(Page $page)
    {
        $this->setPageTitle($page->title);
        $this->breadcrumbs = array(
            $this->getPageTitle(),
        );
        $this->render('view', array(
            'page' => $page
        ));
    }

}
