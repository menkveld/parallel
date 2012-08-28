<?php

/**
 * API Error code to HTML Error Mapping
 * 
 * 0: No Error
 * 20000 - 29999: Success
 * 40000 - 39999: Client Request Errors
 * 50000 - 59999: Server Side Errors
 * 
 */


return array(
	// No Error - All OK
	0 => array(
			'httpStatusCode' => 200,
			'httpMessage' => 'OK',
			'category' => 'Success',
			'developerMessage' => 'OK',
			'userMessage' => 'Request completed successfully',
		),

	40000 => array(
			'httpStatusCode' => 400,
			'httpMessage' => 'Bad Request',
			'category' => 'Client Error',
			'developerMessage' => 'Request error. Do not repeat this request.',
			'userMessage' => 'Please contact the developer of the the application you are using.',
		),
		
	/**
	 * An endpoint is requested that does not exist
	 */
	40400 => array(
			'httpStatusCode' => 404,
			'httpMessage' => 'Not Found',
			'category' => 'Client Error',
			'developerMessage' => 'The requested endpoint API endpoint does not exist. Please refer to the documentation for valid API endpoints.',
			'userMessage' => 'Please contact the developer of the the application you are using.',
		),

	/**
	 * A version of the API has been requested that does not exist.
	 */
	40401 => array(
			'httpStatusCode' => 404,
			'httpMessage' => 'Not Found',
			'category' => 'Client Error',
			'developerMessage' => 'The requested API version does not exists. Please refer to the latest documentation for API version information.',
			'userMessage' => 'Please contact the developer of the the application you are using.',
	),

	/**
	 * A resource type has been requested that is not supported by the server.
	 */
	40402 => array(
			'httpStatusCode' => 404,
			'httpMessage' => 'Not Found',
			'category' => 'Client Error',
			'developerMessage' => 'The requested resource type does not exist. E.g. you requested /usre in stead of /user or you requested an iilegal resource type e.g. /foobar',
			'userMessage' => 'Please contact the developer of the the application you are using.',
	),
	
	40410 => array(
			'httpStatusCode' => 404,
			'httpMessage' => 'Not Found',
			'category' => 'Client Error',
			'developerMessage' => 'No resource requested. Most likely called the API endpoint with no further parameters. This API does not return any resources by default, please explicitly request resources.',
			'userMessage' => 'Please contact the developer of the the application you are using.',
		),	

	40420 => array(
			'httpStatusCode' => 404,
			'httpMessage' => 'Not Found',
			'category' => 'Client Error',
			'developerMessage' => 'The requested resource was not found. Check that the requested resource ID value is correct.',
			'userMessage' => 'Please contact the developer of the the application you are using.',
	),
	
	// Server errors
	50000 => array(
			'httpStatusCode' => 500,
			'httpMessage' => 'Internal Server Error',
			'category' => 'Server Error',
			'developerMessage' => 'The server can not currently respond to your request. Please try again later or refer the the website for current service status.',
			'userMessage' => 'We are currently experiencing tecnical difficulty, please try again later.',
	),		

	/*
	 * Use this when some part of a protocol should be implemented but is not. 
	 * E.g. if some flow of OAuth 2.0 is requested but this has not yet been implemented.
	 */
	50100 => array(
			'httpStatusCode' => 500,
			'httpMessage' => 'Not Implemented',
			'category' => 'Server Error',
			'developerMessage' => 'Requested functionality has not yet been implemented.',
			'userMessage' => 'We are currently experiencing tecnical difficulty, please try again later.',
	),
);