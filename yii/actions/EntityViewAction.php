<?php
namespace parallel\yii\actions;

class EntityViewAction extends \parallel\yii\Action {
	/**
	 * Entity model name
	 */
	public $model;

	public $readView = 'view';
	
	public function run() {
		// Apply access restrictions if applicable - see \parallel\yii\Action for variable definition
		if(\Yii::app()->user->checkAccess($this->authItem) || !$this->restrictAccess) {
			
			$model = \parallel\yii\ActiveRecord::model($this->model)->findByPk($_GET['id']);
			$this->controller->render($this->readView, array(
					'model'=>$model,
			));
		} else {
			throw new \CHttpException(parent::USER_UNAUTHORISED_STATUS, parent::USER_UNAUTHORISED_MESSAGE);
		}		
	}
}