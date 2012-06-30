<?php
namespace parallel\yii\widgets\Addresses;

class Addresses extends \CWidget {
	
	public $model;

	/**
	 * url of the ajax call to add another contact detail. 
	 */
	public $ajaxAddAddressAction;
	
	public function init() {
		// Default add contact detail action
		if(!isset($this->ajaxAddAddressAction)) {
			$this->ajaxAddAddressAction = 'default/ajaxAddAddress';
		}
	}
	
	public function run() {
		$this->render('AddressesView', 
				array('addressItems' => $this->model->addressItems->displayItems,
					  'ajaxAddAction' => $this->ajaxAddAddressAction)
		);		
	}
}