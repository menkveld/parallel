<?php
namespace parallel\yii\widgets\ContactDetails;

class ContactDetails extends \CWidget {
	
	public $model;

	/**
	 * url of the ajax call to add another contact detail. 
	 */
	public $ajaxAddContactDetailAction;
	
	public function init() {
		// Default add contact detail action
		if(!isset($this->ajaxAddContactDetailAction)) {
			$this->ajaxAddContactDetailAction = 'default/ajaxAddContactDetail';
		}
	}
	
	public function run() {
		$this->render('ContactDetailsView', 
				array('contactDetailItems' => $this->model->contactDetailItems->displayItems,
						'ajaxAddAction' => $this->ajaxAddContactDetailAction)
		);		
	}
}