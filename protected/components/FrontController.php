<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontController extends CController
{

    public $layoutPath;
    public $layout = '//layouts/main';
    private $mainMenuItems;

    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();

    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    public $companyName;

    /**
     * Client script object
     *
     * @var CClientScript 
     */
    private $_cs;

    /**
     * Returns link for clientScript
     *
     * @return CClientScript object
     */
    public function getCs()
    {
        return $this->_cs;
    }

    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    public function getCompanyName()
    {
        return $this->companyName;
    }

    public function setMetaKeywords($keywords)
    {
        //Yii::app()->clientScript->registerMetaTag($keywords, 'keywords', null, array(), 'meta_keywords');
    }

    public function setMetaDescription($description)
    {
        Yii::app()->clientScript->registerMetaTag($description, 'description', null, array(), 'meta_description');
    }

    /**
     * This method initials the layout path in FrontController
     */
    private function initLayoutPath()
    {
        $this->setLayoutPath('application.views.layouts');
    }

    /**
     * Sets the layout folder path
     * 
     * @param sting $path Dotted path to 
     */
    protected function setLayoutPath($path)
    {
        $this->layoutPath = Yii::getPathOfAlias($path);
    }

    public function init()
    {
        $this->setCompanyName(Config::get('company'));
        $this->setMetaDescription(Config::get('meta_description'));
        //$this->setMetaKeywords(Config::get('meta_keywords'));
        $this->setPageTitle(Config::get('title'));
        $this->initLayoutPath();
        Yii::app()->clientScript->registerCoreScript('jquery');
        //Yii::app()->clientScript->registerCssFile('/css/style.css');
        Yii::app()->clientScript->registerCssFile('/css/bootstrap.min.css');
        //Yii::app()->clientScript->registerScriptFile('/js/bootstrap.min.js');
        return parent::init();
    }

    /**
     * @return array
     */
    public function getCategoryItems()
    {
        if (($category_id = isset($_GET['category_id']) ? (int) $_GET['category_id'] : null) !== null) {
            return $this->getMenuItemsByCategoryId($category_id);
        }
        return $this->getDefaulMenuItems();
    }

    private function getDefaulMenuItems()
    {
        $categories = Categories::model()->enabled()->findAll('parent_category_id=:parent_category_id', array(
            ':parent_category_id' => 0
        ));
        $menuItems = array();
        foreach ($categories as $category) {
            $menuItems[] = array(
                'label' => $category->title,
                'url' => $category->getFrontUrl()
            );
        }
        return $menuItems;
    }

    /**
     * @param int|string $category_id
     * @return array
     */
    private function getMenuItemsByCategoryId($category_id)
    {
        $activeCategory = Categories::model()->findByPk($category_id);

        $rootCategories = Categories::model()->enabled()->findAll('parent_category_id=:parent_category_id', array(
            ':parent_category_id' => 0
        ));

        $menuItems = array();

        foreach ($rootCategories as $category) {
            $subItems = array();
            if ($category->category_id == $category_id || ($activeCategory->parent && $category->category_id == $activeCategory->parent->category_id)) {
                foreach ($category->children as $childCategory) {
                    $subItems[] = array(
                        'label' => $childCategory->title,
                        'url' => $childCategory->getFrontUrl(),
                        'active' => $childCategory->category_id == $category_id
                    );
                }
            }
            $menuItems[] = array(
                'label' => $category->title,
                'url' => $category->getFrontUrl(),
                'items' => $subItems,
                'active' => ($category->category_id == $category_id) || $subItems
            );
        }

        return $menuItems;
    }

    private function getMenuItemsByProductId($product_id)
    {
        return array();
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
                    'class' => ($requestUri === $url) ? 'class="active"' : null,
                );
            }
        }
        return $this->mainMenuItems;
    }

}