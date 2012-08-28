<?php
namespace parallel\yii\modules\oauth2;

class OAuth2Module extends \parallel\yii\WebModule
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
