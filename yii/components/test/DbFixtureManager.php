<?php
namespace parallel\yii\test;

\Yii::import('system.test.CDbFixtureManager');
class DbFixtureManager extends \CDbFixtureManager {
	
	/**
	 * 
	 * Override the getDbConnection method of CDbFixtureManager so that we can get 
	 * different database connection and not just the default 'db'
	 * @throws CException
	 */
	public function getDbConnection() {
		$db = \Yii::app()->getComponent($this->connectionID);
		if(!$db instanceof \CDbConnection)
			throw new \CException(Yii::t('yii','CDbTestFixture.connectionID "{id}" is invalid. Please make sure it refers to the ID of a CDbConnection application component.',
				array('{id}'=>$this->connectionID)));
		return $db;
	}
	
}