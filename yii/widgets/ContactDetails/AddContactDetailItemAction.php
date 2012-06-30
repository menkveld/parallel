<?php
namespace parallel\yii\widgets\ContactDetails;

/**
 * 
 * This class defines an action that can be used with any controller
 * to provide ajax creation of a new contact detail widget.
 * 
 * @author Anton Menkveld
 *
 */
class AddContactDetailItemAction extends \CAction {    
    public $contactDetailModel = "parallel\yii\models\ContactDetails\ContactDetail";
    
    public $contactDetailTypeModel = "parallel\yii\models\ContactDetails\ContactDetailType";
    
    public $bridgeModel;

    public function init() {
    }
    
	public function run() {
		$cd = new $this->contactDetailModel;
		$ccd = new $this->bridgeModel;	
		$cdt = \parallel\yii\ActiveRecord::model($this->contactDetailTypeModel)->findByPk($_GET['type']);
		
		$cd->type = $cdt;
		$cd->type_id = $_GET['type'];
		
		$ccd->contactDetail = $cd;
		$ccd->label = $cdt->label;
		
		// Return the Contact Detail Widget
		$this->controller->widget('parallel\yii\widgets\ContactDetails\ContactDetailItem', array(
				'model' => $ccd,
				'index' => $_GET['index'],
			)
		);
		
		// End output
		\Yii::app()->end();
	}
}