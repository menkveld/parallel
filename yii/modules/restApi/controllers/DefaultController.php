<?php

class DefaultController extends PpController
{	
	// REST Actions
	public function actionList()
	{
	}
	
	public function actionView()
	{
	}
	
	public function actionCreate()
	{
	}
	
	public function actionUpdate()
	{
	}
	
	public function actionDelete()
	{
		// Check that user is authorised to perform this action
		// Apply access restrictions if applicable - see \parallel\yii\Action for variable definition
		//if(\Yii::app()->user->checkAccess($this->authItem) || !$this->restrictAccess) {
			
		//}
		
		// Get the model
		$model = $this->_model($_GET['model'], $_GET['id']);
		
		// Perform the action
		if(!$model->delete()) {
			throw new CHttpException(\parallel\yii\action::INTERNAL_SERVER_ERROR_STATUS, \parallel\yii\action::INTERNAL_SERVER_ERROR_MESSAGE);
		}
		
		// Send the response
		$this->_sendResponse();		
	}

	private function _model($modelName, $id = 0) {
		$model = null;
		// Attempt to create an instance of the model requested
		if($id > 0) {
			$model = \parallel\yii\ActiveRecord::model($modelName)->findByPk($id);
		} else {
			//$model = new \parallel\yii\ActiveRecord($modelName);
		}

		// Check that model exists
		if($model!==null && $model instanceof \parallel\yii\ActiveRecord) {
			return $model;
		} else {
			throw new CHttpException(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS, \parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}
	}
	
	private function _sendResponse($data = '', $content_type = 'text/html')
	{
		// set the status
		$status_header = 'HTTP/1.1 ' . \parallel\yii\action::OK_STATUS . ' ' . \parallel\yii\action::OK_STATUS_MESSAGE;
		header($status_header);

		// Create response body based on requested content type
		switch($content_type) {
			case 'json':
				header('Content-type: application/json');
				echo json_encode($data);
				break;

			case 'xml':
				header('Content-type: application/xml');
				break;
				
			default:
				header('Content-type: ' . $content_type);
				echo $data;
				break;
		}

		// Gracefully end the application
		\Yii::app()->end();
	}
}