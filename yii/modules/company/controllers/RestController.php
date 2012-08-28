<?php

class RestController extends \parallel\yii\controllers\RestController{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "parallel\yii\modules\company\models\Company";
	
	public function actionDeleteContactDetail() {
		$model = CompanyContactDetail::model()->findByPk($_GET['id']);
		
		if(!empty($model)) {
			if($model->delete()) {
				$this->sendResponse(array());
			} else {
				// Could not delete Company Contact Details
				
			}
		} else {
			// Specified company contact detail not found
			$this->sendResponse(\parallel\yii\action::RESOURCE_NOT_FOUND_STATUS,
					\parallel\yii\action::RESOURCE_NOT_FOUND_MESSAGE);
		}
	}
	
	public function actionReadContactDetail() {
		echo "Read contact details ".$_GET['id'];
	}
}