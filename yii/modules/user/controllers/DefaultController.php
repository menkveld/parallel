<?php
namespace parallel\yii\modules\user\contollers;

class DefaultController extends \parallel\yii\Controller
{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "User";
	
	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionProfile() {
		
	}
}