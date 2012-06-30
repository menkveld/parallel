<?php
namespace parallel\yii\behaviors;

/**
 * This behavior should be added to models that use bridge tables to point to 
 * referenced objects (detail items) that should be deleted when the original object (entity object) is deleted.
 * E.g. Company has CompanyContactDetails that point to Company and ContactDetail.
 * If a Company is deleted the On Cascade action of the database will delete the entries in
 * CompanyContactDetails but will leave the entries in ContactDetail orphaned. Since ContactDetails
 * does not have(and can not have) a foreign key pointing to CompanyContactDetails as it is also used by
 * PersonContactDetails for instance. ContactDetail must be deleted by the application. Hence this behavior.
 * 
 * @author Anton Menkveld
 *
 */
class CleanOrphanedObjects extends \CActiveRecordBehavior {

	/**
	 * An array representing the relationships from the owner model to the bridge model and then to the
	 * detail item(s). E.g. Company->contactDetails->contactDetail the array would be array('contactDetails', 'contactDetail') 
	 * 
	 * ToDo: Currently the relation chain can only be 2 deep, in other words have a bridge model pointing to a detail item model.
	 * 		 It should be extended so that the models that the detail item model is pointing to, can also be included.
	 */
	public $relationChain;
		
	private $_arrOrphanedObjects = array();
	
	public function beforeDelete($event) {
		// Get all the AR's of all the objects that will be orphaned by this delete
		$bridgeObjects = $this->owner->{$this->relationChain[0]};
		foreach($bridgeObjects as $bridgeObject) {
			$this->_arrOrphanedObjects[] = $bridgeObject->{$this->relationChain[1]};
		}
		
		return parent::beforeDelete($event);
	}
	
	public function afterDelete($event) {
		foreach($this->_arrOrphanedObjects as $orphanedObject) {
			// TODO: Check that no other object are referencing this contact detail. It is possible that more
			// than one entity share a telehone number, email address, web address etc.
			// Foreign key contraint should fail in anycase.
			// Maybe just use a try/catch here to catch the exception and simply skip the object
			
			try {
				// delete will give a foreign key constraint if other entities are still 
				// referencing this contact detail
				$orphanedObject->delete();
			} catch(\CDbException $e) {
				// TODO: Check to see that this is in fact a foreign key constraint
				// and not some other exception.
				\Yii::log("Orphaned object skipped due to foreign key contraint.", "info", "parallel.yii.behaviors.CleanOrphanedObjects");
			}
		}
		return parent::afterDelete($event);
	}
}