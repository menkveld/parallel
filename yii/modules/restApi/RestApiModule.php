<?php
namespace parallel\yii\modules\restApi;

class RestApiModule extends \CWebModule
{
	public $resource_map = array();
	
	public function init()
	{		
		// Setup the REST URL Manager Rules
		// TODO: Move url manager rule definition here
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
}
