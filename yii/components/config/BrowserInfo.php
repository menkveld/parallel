<?php
namespace parallel\yii\components\config;
require_once(\Yii::getPathOfAlias('vendors').'/browser/Browser.php');
/**
 * 
 * This class wrapper for the Browser class. It is wrapped in a Yii Application Component 
 * so that it can be loaded as such.
 * 
 * This also serves as an example of how to wrap third party classes into Yii Application Components
 * 
 * @author Anton Menkveld
 *
 */
class BrowserInfo extends \CApplicationComponent
{
	private $_browser;

	public function init() {
	}

	public function __construct()
	{
		$this->_browser = new \Browser();
	}

	/**
	* Call a Browser function
	*
	* @return string
	*/
	public function __call($method, $params)
	{
		if (is_object($this->_browser) && get_class($this->_browser)==='Browser') { 
			return call_user_func_array(array($this->_browser, $method), $params);
		} else {
			throw new \CException(\Yii::t('Browser', 'Can not call a method of a non existent object'));	
		}
	}
}
