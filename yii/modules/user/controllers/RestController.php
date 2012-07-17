<?php
//namespace parallel\yii\modules\user\contollers;

class RestController extends \parallel\yii\RestController
{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "parallel\yii\modules\user\models\User";

	/**
	 * Returns the user profile data
	 */
	public function actionReadProfile() {
		$user = \parallel\yii\EntityActiveRecord::model($this->_model)
						->with('person','usernameContactDetail')
						->findByPk($_GET['id']);

		// Create the user profile
		$profile = array();
		$profile['name'] = $user->person->preferred_name;
		$profile['surname'] = $user->person->surname;
		$profile['email'] = $user->usernameContactDetail->value;
		
		
		if(!empty($user)) {
			$this->sendResponse(\parallel\yii\action::OK_STATUS,
							\parallel\yii\action::OK_STATUS_MESSAGE,
							$profile);
		} else {
			$this->sendResponse(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS,
								\parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}
	}

	/**
	 * Updates user profile data
	 */
	public function actionUpdateProfile() {
		$jsonUser = file_get_contents('php://input');
		$arrUser = CJSON::decode($jsonUser,true);

		// Get the user object
		$user = \parallel\yii\EntityActiveRecord::model($this->_model)->findByPk($_GET['id']);
		
		// Update the user object
		$user->person->preferred_name = $arrUser['name'];
		$user->person->surname = $arrUser['surname'];
		
		$error = array();
		// Create a new email address if a new 
		if($arrUser['email']!=$user->usernameContactDetail->value) {
			$cd = new \parallel\yii\models\ContactDetails\ContactDetail;
			$cd->value = $arrUser['email'];
			$cd->type_id = 2;
			if(!$cd->validate()) {
				$error['email'] = $cd->errors['value'][0];
			}

			// Update the username contact detail in the user table
			//$user->username_contact_detail_id = $cd_id; 
		}
		
		// Save Active Records
		if(!$user->person->validate()) {
			if(array_key_exists('preferred_name', $user->person->errors)) {
				$error['name'] = $user->person->errors['preferred_name'];
			}
			if(array_key_exists('surname', $user->person->errors)) {
				$error['surname'] = $user->person->errors['surname'];
			}
		} else {
			$user->person->save();
		}
		
		if(!empty($error)) {
			$this->sendResponse(\parallel\yii\action::UNPROCESSABLE_ENTITY_STATUS,
					\parallel\yii\action::UNPROCESSABLE_ENTITY_MESSAGE,
					$error);
		} else {			
			// Send updated user back
			$this->sendResponse(\parallel\yii\action::OK_STATUS,
								\parallel\yii\action::OK_STATUS_MESSAGE);
		}
	}
}