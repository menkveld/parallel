<?php
namespace parallel\yii\controllers;

/**
 * This class is a base class for all module RestController
 * 
 * @author Anton Menkveld
 *
 */
class RestController extends \parallel\yii\Controller {

	/**
	 * Create a new resource of given type
	 * e.g. POST /user
	 */
	public function actionCreate() {
		
	}
	
	/**
	 * List requested resources
	 * e.g. GET /user
	 */
	public function actionList() {
		// ToDo: Add security, Add pagination
		
		// Get a list of all the required resources
		$models = \parallel\yii\ActiveRecord::model($this->_model)->findAll();
		
		// Return the list to the client
		$this->sendResponse($models);
	}
		
	/**
	 * Bulk update of selected resources
	 * e.g. PUT /user
	 */
	public function actionBulkUpdate() {
		
	}
	
	/**
	 * Delete all selected resources
	 * e.g. DELETE /user
	 */
	public function actionDeleteAll() {
		
	}
	
	/**
	 * Return the ative record of the given model and ID
	 * e.g. GET /user/23
	 */
	public function actionRead() {
		// Get the model
		$model = $this->loadModel($_GET['id']);

		// Send response
		if($model) {
			$this->sendResponse($model);
		} else {
			// Requested resources not found
			throw new \parallel\yii\components\exceptions\ApiException(40420);
		}
	}
	
	/**
	 * Updates a specific resource.
	 * e.g. PUT /user/23
	 */
	public function actionUpdate() {
	}

	/**
	 * Delete a specific resource
	 * e.g. DELETE /user/23
	 */
	public function actionDelete()
	{
		// Get the model
		$model = $this->loadModel($_GET['id']);
	
		// Perform the action
		if(!$model->delete()) {
			throw new CHttpException(\parallel\yii\action::INTERNAL_SERVER_ERROR_STATUS, \parallel\yii\action::INTERNAL_SERVER_ERROR_MESSAGE);
		}
	
		// Send the response
		$this->_sendResponse();
	}
	
	/**
	 * Method will convert the given data into the requested format and return it to the client code.
	 *
	 * @param unknown_type $data
	 * @param unknown_type $content_type
	 */
	protected function sendResponse($data = '', $content_type = 'json')
	{
		if($data!==null) {
			// set the status
			$status_header = 'HTTP/1.1 200 OK';
			header($status_header);
		
			if($data!='') {
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
			}
		} else {
			throw new CHttpException(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS, \parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}
			
		// Gracefully end the application
		\Yii::app()->end();
	}
	
	protected function sendError($error, $content_type = 'json') {
		
	}
}