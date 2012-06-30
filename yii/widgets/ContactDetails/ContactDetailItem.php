<?php
namespace parallel\yii\widgets\ContactDetails;

class ContactDetailItem extends \CWidget {

	public $model;
	
	public $index;
	
	public function init(){
		
	}
	
	public function run() {
		
		$this->render('ContactDetailItemView', 
				array('contactDetailItem' => $this->model,
					  'index' => $this->index)
		);		
	}
}