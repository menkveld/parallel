<?php
namespace parallel\yii\components\config;
/**
 * 
 * Application component to manage global system configuration.
 * 
 * @author Anton Menkveld
 *
 */
class Config extends \CApplicationComponent {
	public $systemConfigFilePath;
	public $systemConfigFile = 'system.xml';
	public $clientConfigFile;
	
	private $_system;
	private $_client;
	
	public function init() {
		// Create the system Zend_Config object
		if(!isset($this->systemConfigFilePath)) {
			$this->systemConfigFilePath = \Yii::getPathOfAlias('application.config');
		}
		$systemFile = $this->systemConfigFilePath.'/'.$this->systemConfigFile;
		if(!isset($this->_system)) {
			try {
				$this->_system = new \Zend_Config_Xml($systemFile);
			} catch(\Zend_Config_Exception $e) {
				throw new \CException('System config file '.$this->systemConfigFilePath.'/'.$this->systemConfigFile.' not found. Zend_Config_Exception: '.$e->getMessage());
			}
		}
		
		// Create the client Zend_Config object
	}
	
	public function __get($name) {
		switch($name) {
			case 'system' :
				if($this->_system===null || !($this->_system instanceof \Zend_Config_Xml)) {
					throw new \CException('System config file '.$this->systemConfigFilePath.'/'.$this->systemConfigFile.' not found.');
				}
				return $this->_system;
				
			case 'client' :
				if($this->_client===null || !($this->_client instanceof \Zend_Config_Xml)) {
					// Try and open the client config file
					try {
						$this->_client = new \Zend_Config_Xml($this->clientConfigFile);
					} catch (\Zend_Config_Exception $e) {
						throw new \CException('Client config file '.$this->clientConfigFilePath.' not found or invalid. Zend_Config_Exception: '.$e->getMessage());
					}
				}
				return $this->_client;
		}
	}
}