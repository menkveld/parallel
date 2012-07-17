<?php
namespace parallel\yii;
/**
 * 
 * Parallel system wide base class for CAction
 * 
 * @author Anton Menkveld
 *
 */
class Action extends \CAction {
	
	const OK_STATUS_MESSAGE = 'OK';
	const OK_STATUS = 200;
	const CREATED_STATUS_MESSAGE = "CREATED";
	const CREATED_STATUS = 201;
	
	const INVALID_REQUEST_MESSAGE = 'Invalid request. Please do not repeat this request.';
	const INVALID_REQUEST_STATUS = 400;
	const USER_UNAUTHORISED_MESSAGE = 'User is not authorised to perform the requested action. Please log in or change user access rights.';
	const USER_UNAUTHORISED_STATUS = 401;
	const RESOURCE_NOT_FOUND_MESSAGE = 'Requested resource not found.';
	const RESOURCE_NOT_FOUND_STATUS = 404;
	const METHOD_NOT_ALLOWED_MESSAGE = 'Requested method not allowed.';
	const METHOD_NOT_ALLOWED_STATUS = 405;
	
	const UNPROCESSABLE_ENTITY_MESSAGE = "Could not process entity";
	const UNPROCESSABLE_ENTITY_STATUS = 422;
	
	const INTERNAL_SERVER_ERROR_MESSAGE = 'Internal Server Error';
	const INTERNAL_SERVER_ERROR_STATUS = 500;
	
	/**
	 * Access restrictions should be applied.
	 */
	public $restrictAccess = true;
	
	/**
	 * Authorisation item required for this action. Only applicable if $restrictAccess is true.
	 */
	public $authorisationItem;
	
	/**
	 * Returns the authorisation item.
	 * If not set, it will default to ModelName.ActionName
	 *
	 */
	public function getAuthItem() {
		if(!isset($this->authorisationItem)) {
			// Auth item is not specified default to ModelName.ActionName
			// e.g. Company.create or Person.update
			$this->authorisationItem = $this->model.'.'.$this->id;
		}
		return $this->authorisationItem;
	}
}