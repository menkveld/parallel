<?php
namespace parallel\yii\components\exceptions;

class ApiExceptionHandler {
	
	public static function handleApiException(\CExceptionEvent $event) {
		$exception  = $event->exception;
		
		// If this is a 404 error, remap to API exception
		if(get_class($exception) == 'CHttpException' && $exception->statusCode === 404) {
			$exception = new ApiException(40400);
		}
		
		if(get_class($exception) == 'parallel\yii\components\exceptions\ApiException') {
			$status_header = 'HTTP/1.1 ';
			if($exception->suppress_http_status_code) {
				$status_header .= $status_header.'200 OK';
			} else {
				$status_header .= $exception->statusCode.' '.$exception->getError('httpMessage');
			}
	
			header($status_header);
			header('Content-type: application/'.$exception->format);
			
			$body['apiErrorCode'] = $exception->apiError;
			$body['category'] = $exception->getError('category');
			$body['developerMessage'] = $exception->getError('developerMessage');
			$body['userMessage'] = $exception->getError('userMessage');
			//$body['moreInfo'] = \Yii::app()->params['developerWebsiteUrl'].'/errors/'.$exception->apiError;
			
			echo \CJSON::encode($body);
			$event->handled = true;
		}
	}
}