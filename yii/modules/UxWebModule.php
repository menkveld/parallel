<?php
namespace parallel\yii\modules;

/**
 * 
 * Base class for all parallel web modules
 * 
 * @author Anton Menkveld
 *
 */
class UxWebModule extends \parallel\yii\WebModule {
	
	/**
	 * This function will publish assets, if not yet published
	 * and return the assigned url
	 * 
	 */
	public function getAssetsUrl($forceCopy=false)
	{
		if ($this->_assetsUrl === null)
			$this->_assetsUrl = \Yii::app()->assetManager->publish($this->basePath.'/assets', false, -1, $forceCopy);
		return $this->_assetsUrl;
	}
	
	/**
	 *  Store url of assigned asset directory
	 */
	private $_assetsUrl;
}