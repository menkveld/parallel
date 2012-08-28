<?php
namespace parallel\yii\components\exceptions;

class ApiException extends \CHttpException {
	public $apiError;
	public $format;
	public $suppress_http_status_code;
	
	public function __construct($error, $suppress_http_status_code = false, $format='json') {
		$this->apiError = $error;
		$this->suppress_http_status_code = $suppress_http_status_code;
		$this->format = $format;
		
		// Instantiate CHttpException
		parent::__construct($this->getError('httpStatusCode'), $this->getError('userMessage'), $error);
	}
	
	public function getError($item) {
		if($this->_arr_errors === null) {
			$this->_arr_errors = include("ApiErrors.php");	
		}
		return $this->_arr_errors[$this->apiError][$item];
	}
	
	private $_arr_errors;
}