<?php
namespace parallel\yii\modules\user;

class UserModule extends \parallel\yii\UxWebModule
{		
	public function init()
	{	
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
