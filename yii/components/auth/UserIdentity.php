<?php
namespace parallel\yii\components\auth;
/**
 * Parallel system wide base class for CUserIdentity
 * 
 * This class provides the authenticate method that will verify the
 * user's username and password.
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
	public function __construct($username, $password, $instance = null) {
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
	
	/**
	 * Authenticates a user against the user table in the
	 * local database.
	 *
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticateLocalDatabase()
	{
		// Find the user based on his email address
		$user = \parallel\yii\modules\user\models\User::model()->with(array(
					'usernameContactDetail' => array(
								'value' => $this->username,
							),
				))->find(); 
		
		// Check the user
		if(empty($user)) {
			// username not found
			$this->errorCode = self::ERROR_USERNAME_INVALID;			
		} else {
			// Check the password
			if($user->password !== $user->encryptPassword($this->password)) {
				// password incorrect
				$this->errorCode = self::ERROR_PASSWORD_INVALID;				
			} else {
				// All good, username and password match
				$this->errorCode = self::ERROR_NONE;
				
				// Set user states
				$this->setState('id', $user->id);
			}
		}
		return !$this->errorCode;	// Returns true is errorCode = 0
	}
	
	/**
	 * This method will authenticate the current user credentials with the Platypus service.
	 * 
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticatePlatypus() {
		
	}
}