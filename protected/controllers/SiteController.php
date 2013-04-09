<?php

class SiteController extends FrontController
{

	public function actionIndex()
	{
		/**Yii::app()->clientScript->registerCssFile('/css/nivo-slider.css');
		Yii::app()->clientScript->registerScriptFile('/js/jquery.nivo.slider.js');
		Yii::app()->clientScript->registerScript('start.nivo.slider', '
			$(window).load(function() {
                $(\'#slideshow\').nivoSlider({
                    directionNav: false
                });
            });
		');
		$this->render('index');*/
	}

	public function actionPage($page_id)
	{
		/**if (($page = Page::model()->findByPk($page_id)) === null || $page->enabled == false)
			throw new CHttpException(404);

		if ($page->meta_title)
			$this->setPageTitle($page->meta_title);

		if ($page->meta_keywords)
			$this->setMetaKeywords($page->meta_keywords);

		if ($page->meta_description)
			$this->setMetaDescription($page->meta_description);

		$this->render('page', array(
			'page' => $page
		));**/
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if ($error = Yii::app()->errorHandler->error) {
			if (Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Contact page with
	 */
	public function actionContact()
	{
		$this->setPageTitle(Config::get('contact_meta_title'));
		$this->setMetaDescription(Config::get('contact_meta_description'));
		$this->setMetaKeywords(Config::get('contact_meta_keywords'));
		$message = new Message;
		if ($_POST['Message']) {
			$message->attributes = $_POST['Message'];
			if ($message->validate()) {
				$message->save(false);
				Yii::app()->user->setFlash('success', Config::get('msg_send_success'));
				$this->redirect(array('site/contact'));
			}
		}

		$this->render('contact', array(
			'message' => $message
		));
	}

}