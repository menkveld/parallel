<?php
namespace parallel\yii\modules\restApi;

class RestApiModule extends \CWebModule
{
	/**
	 * Default return format
	 * Valid:
	 *     json
	 *     xml
	 */
	public $defaultFormat = 'json';
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
				'person.models.*',
				'company.models.*',
		));
		
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
