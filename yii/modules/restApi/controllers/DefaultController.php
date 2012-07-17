<?php

class DefaultController extends \parallel\yii\Controller
{	
	// REST Actions
	public function actionIndex()
	{
	}
	
	public function actionRead()
	{
		// Get the model
		$model = $this->_loadModel($_GET['model'], $_GET['id']);
		$this->_sendResponse($model);
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
		$model = $this->_loadModel($_GET['model'], $_GET['id']);
		
		// Perform the action
		if(!$model->delete()) {
			throw new CHttpException(\parallel\yii\action::INTERNAL_SERVER_ERROR_STATUS, \parallel\yii\action::INTERNAL_SERVER_ERROR_MESSAGE);
		}
		
		// Send the response
		$this->_sendResponse();		
	}

	private function _loadModel($model, $id = 0) {
		if(array_key_exists($model, $this->module->resource_map)) {
			$this->model = $this->module->resource_map[$model];
			$model = $this->loadModel($_GET['id']);			
		} else {
			throw new CHttpException(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS, \parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}		
		return $model;
	}
	
	/**
	 * Method will convert the given data into the requested format and return it to the client code.
	 * 
	 * @param unknown_type $data
	 * @param unknown_type $content_type
	 */
	private function _sendResponse($data = '', $content_type = 'json')
	{
		// set the status
		$status_header = 'HTTP/1.1 ' . \parallel\yii\action::OK_STATUS . ' ' . \parallel\yii\action::OK_STATUS_MESSAGE;
		header($status_header);

		// Create response body based on requested content type
		switch($content_type) {
			case 'json':
				header('Content-type: application/json');
				echo \CJSON::encode($data);
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