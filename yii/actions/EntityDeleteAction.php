<?php
namespace parallel\yii\actions;

/**
 * This is a generic action to Delete an entity. It replaces the default delete action create by Gii.
 *
 * This action can work together with parallel\yii\behaviors\DetailItems to update detail items in the same action.
 *
 * @author Anton Menkveld
 *
 */
class EntityDeleteAction extends \parallel\yii\Action {
	/**
	 * Entity model name
	 */
	public $model;

	/**
	 * Where to redirect to on successful update
	 */
	public $onSuccessRedirectTo = "view";
			
	public function run() {
		// Apply access restrictions if applicable - see \parallel\yii\Action for variable definition
		if(\Yii::app()->user->checkAccess($this->authItem) || !$this->restrictAccess) {
			// we only allow deletion via POST request
			if(\Yii::app()->request->isPostRequest)
			{
				$ar = \parallel\yii\ActiveRecord::model($this->model)->findByPk($_GET['id']);
				$ar->delete();
				
				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!(\Yii::app()->request->isAjaxRequest)) {
					if(isset($_POST['returnUrl'])) {
						$this->redirect($_POST['redirectUrl']);
					} else {
						$this->redirect($this->onSuccessRedirectTo);
					}
				}
			} else {
				// The request was not made via POST method.
				throw new CHttpException(parent::METHOD_NOT_ALLOWED_STATUS, parent::METHOD_NOT_ALLOWED_MESSAGE);
			}
		} else {
			throw new \CHttpException(parent::USER_UNAUTHORISED_STATUS, parent::USER_UNAUTHORISED_MESSAGE);
		}		
	}
}