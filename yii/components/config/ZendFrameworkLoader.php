<?php
namespace parallel\yii\components\config;

require_once \Yii::getPathOfAlias('vendors').'/Zend/Loader/Autoloader.php';

class ZendFrameworkLoader extends \CApplicationComponent {
	
	public function init() {
		\Yii::registerAutoloader(array('Zend_Loader_Autoloader', 'autoload'));
	}
}