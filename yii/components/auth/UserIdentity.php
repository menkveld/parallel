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
		
	/**
	 * Authenticates a user against the user table in the
	 * local database.
	 *
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticateLocalDatabase()
	{
		$user = 
		
		// No Errors
		$this->errorCode = self::ERROR_NONE;
		return !$this->errorCode;
		
		// Set dual database models to use system database
		// this will cause the Company model to use the company table
		// in the system database as opposed to the default client database.
		Yii::app()->params['useDatabase'] = parallel\yii\ActiveRecord::SYSTEM_DATABASE;
	
		// Get the company instance
		$instance = ApplicationInstance::model()->findByAttributes(array('instance_id'=>$this->instance));
		if($instance===null) {
			$this->errorCode = self::ERROR_INSTANCE_NOT_FOUND;
		} else {
			// Application instance found
			// Set User Identity Parameters (states)
			$this->setState('instance', $this->instance);
			$this->setState('database', $instance->database);
			$this->setState('filePath', $instance->file_path);
				
			// create instance database connection and active it
			$dsn = Yii::app()->config->system->database->driver.
			":host=".Yii::app()->config->system->database->host.
			";dbname=".$instance->database;
			Yii::trace("Connecting to instance database.\nDSN=".$dsn, "application.components.UserIdentity");
			Yii::app()->db->connectionString = $dsn;
			Yii::app()->db->setActive(true);
	
			// Check username and password
			// Get the user AR from the database for the given username
			$user = User::model()->findByAttributes(array('username' => $this->username));
			if($user === null) {
				// username not found
				$this->errorCode = self::ERROR_USERNAME_INVALID;
			} else {
				// User found, check password
				if($user->password !== $user->encrypt($this->password)) {
					// password incorrect
					$this->errorCode = self::ERROR_PASSWORD_INVALID;
				} else {
					// All good, user authenticated
					$this->_id = $user->id;
						
					// Log successful user login
					Yii::log("Successful user login.\nUsername: ".$this->username.
							"Client: ".$instance->company->short_name." (Client Id: ".$instance->company->id.")\n".
							"Application: ".$instance->application->name."\n".
							"Application Path: ".$instance->application->path."\n".
							"Instance Id: ".$instance->instance_id."\n".
							"Database: ".$instance->database."\n".
							"Data Path: ".$instance->file_path."\n",
							"info", "application.components.UserIdentity");
	
					// Set lastLoginTime
						
					// No Errors
					$this->errorCode = self::ERROR_NONE;
				}
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