<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontController extends CController
{

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
		Yii::app()->clientScript->registerMetaTag($keywords, 'keywords', null, array(), 'meta_keywords');
	}

	public function setMetaDescription($description)
	{
		Yii::app()->clientScript->registerMetaTag($description, 'description', null, array(), 'meta_description');
	}

	public function init()
	{
		$this->setCompanyName(Config::get('company'));
		$this->setMetaDescription(Config::get('meta_description'));
		$this->setMetaKeywords(Config::get('meta_keywords'));
		$this->setPageTitle(Config::get('title'));
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCssFile('/css/style.css');
		Yii::app()->clientScript->registerCssFile('/css/bootstrap.min.css');
		Yii::app()->clientScript->registerScriptFile('/js/bootstrap.min.js');
		return parent::init();
	}

}