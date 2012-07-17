<?php
//namespace parallel\yii\modules\user\contollers;

/**
 * 
 * Default controller to load the user screen.
 * 
 * @author Anton Menkveld
 *
 */
class DefaultController extends \parallel\yii\Controller
{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "parallel\yii\modules\user\models\User";
	
	/**
	 * Load the user single page application
	 */
	public function actionIndex() {
		$model = $this->loadModel(\Yii::app()->user->id);
		$this->render('index', array('model'=>$model));
	}
	
	/**
	 * Render the option menu
	 */
	public function actionOptionMenu() {
	}
	
	/**
	 * Render the Profile page
	 */
	public function actionProfile() {
		$model = $this->loadModel(\Yii::app()->user->id);
		$this->renderPartial('_profile', array('model' => $model));
	}
}