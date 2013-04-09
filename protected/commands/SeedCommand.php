<?php

/**
 * Description of SeedCommand
 *
 * @author shults
 */
class SeedCommand extends CConsoleCommand
{

	public function actionIndex()
	{
		Yii::import('system.test.CDbFixtureManager');
		$fixtureManager = new CDbFixtureManager();
		$fixtureManager->basePath = Yii::getPathOfAlias('application.seeds');
		$fixtureManager->prepare();
	}

}

