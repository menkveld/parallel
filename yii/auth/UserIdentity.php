<?php
namespace parallel\yii\auth;
/**
 * Parallel system wide base class for CUserIdentity
 * 
 * The main purpose of this class is to override the contructor so that
 * it will also accept an instance id.
 * 
 * @author Anton Menkveld
 *
 */

class UserIdentity extends \CUserIdentity {
	
	const ERROR_INSTANCE_NOT_FOUND = 3;
	
	/**
	 * User Identity Properties
	 */
	protected $_id;
	protected $_instance;
	
	/**
	 * Override the original constructor to include the application instance.
	 * 
	 * @param unknown_type $instance
	 * @param unknown_type $username
	 * @param unknown_type $password
	 */
	public function __construct($instance, $username, $password) {
		parent::__construct($username, $password);
		$this->_instance = $instance;
	}
	
	/**
	 * Returns the current user id 
	 */
	public function getId() {
		return $this->_id;
	}
	
	public function getInstance() {
		return $this->_instance;
	}
}