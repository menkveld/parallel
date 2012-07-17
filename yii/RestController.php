<?php
namespace parallel\yii;

/**
 * This class is a base class for all module RestController
 * 
 * @author Anton Menkveld
 *
 */
class RestController extends Controller {

	/**
	 * Generic rest List action
	 */
	public function actionList() {
	}
	
	/**
	 * Generic rest Create action
	 */
	public function actionCreate() {
	}
	
	/**
	 * Generic rest Read action.
	 * 
	 * This method will simply return the ative record of the given model and ID
	 */
	public function actionRead() {
		// Get the model
		$model = $this->loadModel($_GET['id']);

		// Send response
		if(!empty($model)) {
			$this->sendResponse(\parallel\yii\action::OK_STATUS,
							\parallel\yii\action::OK_STATUS_MESSAGE,
							$model);
		} else {
			$this->sendResponse(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS,
								\parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}
	}
	
	/**
	 * Generic rest Update action.
	 */
	public function actionUpdate() {
	}
		

	/**
	 * Generic rest Delete action
	 * @throws CHttpException
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
	protected function sendResponse($status, $status_message, $data = '', $content_type = 'json')
	{
		if($data!==null) {
			// set the status
			$status_header = 'HTTP/1.1 ' . $status . ' ' . $status_message;
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
}