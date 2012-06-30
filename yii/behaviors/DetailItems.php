<?php
namespace parallel\yii\behaviors;

/**
 * This class add the required methods to an entity model to allow the model to interact with 
 * details items. Detail items are for instance contact details, addresses, tags etc. This behaviour assumes
 * that detail items are stored in separate tables and linked to the main entity table with a brige (junction) table. 
 * E.g. ContactDetail contain different types of contact details. CompanyContactDetail is a bridge table connecting
 * ContactDetail to Company. 
 * 
 * For new entities (e.g. Company) the default detail items that should be presented to the user is stored in the instance
 * config file. When DislayItems is called, this class will return an array of AR's containing the detail items that needs to be
 * present by default.
 * 
 * @author Anton Menkveld
 *
 */
class DetailItems extends \CActiveRecordBehavior {

	// PUBLIC MEMBERS
	/**
	 * An array representing the relationships from the owner model to the bridge model and then to the
	 * detail item(s). E.g. Company->contactDetails->contactDetail the array would be array('contactDetails', 'contactDetail') 
	 */
	public $relationChain;
	
	/**
	 * Additional fields (in addition to Label) that should be set in the detail item based on defaults in the config file
	 * E.g. ContactDetail.type_id is defined by type
	 */
	public $configFields = array("type_id" => "type");

	/**
	 * Indicates if an entry must be created for all detail items on the screen as opposed to only the items with values entered by the user. 
	 * All detail items will be validated if this is true, irrespective if the user entered something or not.
	 */
	public $addAllDetailItems = false;
	
	/**
	 * If addAllDetailItems is false, this parameter can be used to indicated which detail item fields to look at to decide if we should validate/create the detail item. 
	 * If any of the detail items fields indicated in this array contain a value, that detail item will be added. E.g. value - if value is specified, we should create
	 * a detail item for that value. Other fields of the detail item can also be specified. 
	 */
	public $addDetailItemFields = array('value');

	/**
	 * If addAllDetailItems is false, this parameter can be used to indicated which bridge item fields to look at when deciding if we should create the detail items.
	 * If any of the bridge item fields indicated in this array contain a value, that detail item will be added. E.g. notes - notes are saved in the bridge table, if
	 * the user specified a note, we want to include that contact detail item, so that if the user specified a note and not a value, value will 
	 * give an error.
	 */
	public $addBridgeItemFields = array('notes');

	/**
	 * Label field in the detail item model (relationChain[0]) that has to be compared to the label field from the config file($defaultItemLabel).
	 * E.g. for contact details, in the config file there is a label entry for each detail item, this has to be matched up with the label field
	 * of contactDetails (company_contact_detail). If you follow the correct naming convention, not change should be required here.
	 */
	public $detailItemLabel = "label";
	public $defaultItemLabel = "label";
	
	
	/**
	 * Method to set the attributes for the detail items. Eg. ContactDetails.
	 * This will update the detail items
	 * 
	 * @param $attributes
	 */
	public function setAttributes($attributes) {
		// Clear the current contents of the arrays
		$this->_formBridgeItems = array();
		$this->_saveBridgeItems = array();
		$this->_formDetailItems = array();
		$this->_saveDetailItems = array();
		
		$formDetailItems = $attributes[$this->detailItemModel];
		$formBridgeItems = $attributes[$this->bridgeItemModel];

		foreach($formBridgeItems as $idx => $formBridgeItem) {
			// Create Detail Item objects
			$formDetailItem = $formDetailItems[$idx];
			// Create Detail Item objects
			$detailItemModel = new $this->detailItemModel;
			// Massive assign attributes 
			$detailItemModel->attributes = $formDetailItem;

			// Add to array
			$this->_formDetailItems[$idx] = $detailItemModel;
			// Create bridge item objects
			$bridgeItemModel = new $this->bridgeItemModel;
			// Explicitely assign id field if available and set record to be existing record
			if(isset($formBridgeItem['id'])) {
				$bridgeItemModel->id = $formBridgeItem['id'];
				$bridgeItemModel->isNewRecord = false;
			}
			
			// Set the Entity ID field of the bridge item to the owner id. E.g. owner is company, set company_id
			//$bridgeItemModel->{$this->bridgeEntityIdField} = $this->owner->id;

			// remove id from post data to avoid warnings of setting unsafe attributes
			unset($formBridgeItem['id']);

			// Massive assign attributes 
			$bridgeItemModel->attributes = $formBridgeItem;
			$bridgeItemModel->{$this->relationChain[1]} = $detailItemModel;
			$this->_formBridgeItems[$idx] = $bridgeItemModel;
		} // foreach	
	}
	
	/**
	 * 
	 * This method is called before the parent(owner) model is validated. This will validate
	 * the detail items.
	 * 
	 * @param $event
	 */
	public function beforeValidate($event) {
		if(!$this->validate()) {
			$event->isValid = false;
		}
		return parent::beforeValidate($event);
	}
	
	/**
	 * 
	 * This method is called after the parent(owner) model is saved. This will save
	 * the detail items.
	 * 
	 * @param $event
	 */
	public function afterSave($event) {
		$this->save();
		return parent::afterSave($event);
	}
	
	/**
	 * 
	 * This method will verify the detail items received from the POST.
	 * It will verify all items if AddAllDetailItems = true. If not, it will
	 * check the fields specified by addDetailItemFields and addBridgeItemFields to see if
	 * values was specified for an item. If values was specified in the specified fields, those
	 * items will be validated.
	 * 
	 */
	public function validate() {	
		$valid = true;	// Assume items are valid until proven otherwise
		// Cycle through each detail item and check validity. At the same time check bridge item validity
		if(!empty($this->_formDetailItems)) {			
			foreach($this->_formDetailItems as $idx => $item) {
				// validate only if addAllDetailItems or if any one of the detail or bridge items fields have a value specified
				$validateDetailItem = false;
				foreach($this->addDetailItemFields as $itemField) {
					$validateDetailItem = $validateDetailItem || !empty($item[$itemField]);
				}
				foreach($this->addBridgeItemFields as $itemField) {
					$validateDetailItem = $validateDetailItem || !empty($this->_formBridgeItems[$idx][$itemField]);
				}
				
				if($this->addAllDetailItems || $validateDetailItem) {
					// Current item has values specified and should thus be validated
						
					$itemValid = $item->validate(); 
					if($itemValid) {
						// Current item is valid and should be saved. Add it to the save array.
//echo "Adding Detail Item ".$this->_formDetailItems[$this->detailItemModel][$idx]->modelName."<BR>";
//echo "Adding Bridge Item ".$this->_formBridgeItems[$this->bridgeItemModel][$idx]->modelName."<BR>";

						$this->_saveDetailItems[$idx] = $this->_formDetailItems[$idx];
						$this->_saveBridgeItems[$idx] = $this->_formBridgeItems[$idx];
					}
					// AND this item validity with the existing validity
					$valid = $itemValid && $valid;
				}
				$this->owner->addErrors($item->errors);
			}
		}
		return $valid;
	}
	
	/**
	 * 
	 * This method is responsible for saving detail items. It will implicitely call validate() to validate the data and populate the
	 * save arrays.
	 * 
	 * It will then create the detail items in the database if required. If a detail item already exists, the bridge item will point to the
	 * existing detail item. 
	 */
	public function save() {
		// Run validate explicitely
		//if($valid = $this->validate()) {
//echo "Saving ".$this->bridgeItemModel."<BR>";		
			// There are detail items to save
			if(!empty($this->_saveDetailItems)) {
				\Yii::trace("Data validated, saving ".$this->relationChain[0], "parallel.yii.behaviors.DetailItems");
				foreach($this->_saveDetailItems as $idx => $item) {
					// Check if detail items already exists
					$dbItem = $item->match();	// Calls on the match behavior to find a matching item. By default match will only return 100% match.
					$dbItem = array_pop($dbItem);
					if(!empty($dbItem)) {
						// Matching item found.
						// Do not save, simply replace item in save array with item from database. For use when saving bridge items.
						$this->_saveDetailItems[$idx] = $dbItem['item'];	// 100% use the first 'item' in the array.
					} else {
						// Detail item not found in database, create new detail item.
						$this->_saveDetailItems[$idx]->save();
					}				
				} // foreach saveDetailItem

				// Update and save all bridge items
				foreach ($this->_saveBridgeItems as $idx => $item) {
					// Set the detail item id (will now be set since detail items was saved earlier
					$this->_saveBridgeItems[$idx]->{$this->bridgeDetailItemIdField} = $this->_saveDetailItems[$idx]->id;
					if(!empty($item->id)) {
						// Bridge item already exists in database
						// Check if we need to supercede the old bridge item or update existing one
						$dbBridgeItem = \parallel\yii\ActiveRecord::model($this->bridgeItemModel)->findByPk($item->id);
						// Check if existing bridge item is pointing to existing detail item
						if($dbBridgeItem->{$this->bridgeDetailItemIdField} != $this->_saveDetailItems[$idx]->id) {
							// Bridge item is pointing to different detail item
							// Set bridge items date_superseded and point formBridgeItem to new detail item
							// Save both objects
							$dbBridgeItem->date_superseded = new \CDbExpression('NOW()');
							$dbBridgeItem->save();
							
							// Remove id from saveBrigeItem, so that a new bridge item will be created. 
							unset($this->_saveBridgeItems[$idx]->id);
							$this->_saveBridgeItems[$idx]->isNewRecord = true;
						}
					} else {
						$this->_saveBridgeItems[$idx]->isNewRecord = true;
					}
					// Set the bridge entity Id field to the id of the owner object
					$this->_saveBridgeItems[$idx]->{$this->bridgeEntityIdField} = $this->owner->id;
					// Save the current bridge item
					$this->_saveBridgeItems[$idx]->save();
				} // foreach saveBridgeItem
			}
			return true;	
		//} else {
		//	return $valid;
		//}
	}

	/**
	 * 
	 * This method returns all the detail item entries (eg. PersonContactDetail) that should be displayed on the entity form.
	 */
	public function getDisplayItems() {		
		// Initialise return array
		$returnDetailItems = array();

		// If items received in form data, simply return these
		if(!empty($this->_formBridgeItems)) {
			// form items are available show only these. This is only applicable when form data is posted.
			$returnDetailItems = $this->_formBridgeItems;
print_r($returnDetailItems);
die();
		} else {
			// Items not received in form data get from database and config file
			// Get current entity detail items already in the database
			$currentDetailItems = $this->owner->{$this->relationChain[0]};
			
			// If no items currently in the database, get default detail items for this instance from the configEntity and configItems in the instance config file
			if(empty($currentDetailItems)) {
				$defaultDetailItems = \Yii::app()->config->client->{$this->configEntity}->{$this->configItem};
				\Yii::trace("Adding ".count($defaultDetailItems)." default detail items from client instance config file node: ".$this->configEntity."->".$this->configItem, "parallel.yii.behaviors.DetailItems");
			} else {
				// There are entries in the database, simply return them
				\Yii::trace("Adding ".count($currentDetailItems)." ".$this->detailItemModel." items.", "parallel.yii.behaviors.DetailItems");
				$returnDetailItems = $currentDetailItems;
			}

			// There are no items in the database but we have entries in the config file - so create objects from those entries
			// This process will create a chain of AR object as per the given relationChain.		
			if(!empty($defaultDetailItems) && empty($currentDetailItems)) {
				
				foreach($defaultDetailItems as $idx => $defaultDetailItem) {
					// Add all required AR models in the relationChain
					$relationModels = array();	// Initiakuse array to hold all AR models
					$currentModelRelations = $this->owner->relations();	// Start with owner model
					foreach($this->relationChain as $relation) {
						$newModel = new $currentModelRelations[$relation][1];
						$relationModels[$relation] = $newModel; 
						$currentModelRelations = $newModel->relations();
						$currentRelation = $relation;	// store for use in next for loop
					}

					// Create the complete bridge item
					$currentModel = null;	// Current model is null - so can not point to it
					$revRelationModels = array_reverse($relationModels);
					foreach($revRelationModels as $relation => $model) {
						// Set specific instance of an object if specified in the config file, otherwise
						// just set the pointer the the current model
						// E.g. for addresses you might specify proviceState='8', this which case
						// provinceState object should be the object with PK=8
						$configDefault = $defaultDetailItem->{$relation};
						
						// If value is empty, check that there is not global setting for this
						if(empty($configDefault)) {
							$globalDefault = \Yii::app()->config->client->globalDefaults->{$relation};
							if(isset($globalDefault)) {
								$configDefault = $globalDefault;
							}
						}
						
						if(isset($configDefault)) {
							$model = \parallel\yii\ActiveRecord::model($model->modelName)->findByPk($configDefault);								
						} else {
							if($currentModel!==null) {
								$model->{$currentRelation} = $currentModel;
							}
						}
						$currentModel = $model;
						$currentRelation = $relation;
					}
					
					// Set detail item config fields
					foreach($this->configFields as $itemField => $configField) {
						$currentModel->{$this->relationChain[1]}->{$itemField} = $defaultDetailItem->{$configField};
					}

					// Set the label field
					$label = $defaultDetailItem->{$this->defaultItemLabel};
					if(isset($label)) {
						$currentModel->{$this->detailItemLabel} = $label;
					}
					$returnDetailItems[] = $currentModel;
				}	// Default Items
			}
		}
		// Return the detail items		
		return $returnDetailItems; 
	}
	
	// PROTECTED MEMBERS
	/** 
	 * Returns the class name of the model that represents the database bridge tabel for this detail item.
	 * This is found in the relations array of the owner. Examples of these are PersonContactDetail or CompanyAddress
	 * 
	 * If the property is already set, return the current value.
	 */
	protected function getBridgeItemModel() {
		if(!isset($this->_bridgeItemModel)) {
			$relations = $this->owner->relations();
			$this->_bridgeItemModel = $relations[$this->relationChain[0]][1]; // See the relations method of the owner model class
		}
		return $this->_bridgeItemModel;
	}
	protected function setBridgeItemModel($value) {
		$this->_bridgeItemModel = $value;
	}
	
	/**
	 * Returns the name of the model that represent the detail item. E.g. ContactDetail or Address 
	 * This is the model that the bridge model is referencing and is read from the relations array of the bridge model
	 */
	protected function getDetailItemModel() {
		if(!isset($this->_detailItemModel)) {
			$bridgeRelations = \parallel\yii\ActiveRecord::model($this->bridgeItemModel)->relations();
			$this->_detailItemModel = $bridgeRelations[$this->relationChain[1]][1];
		}
		return $this->_detailItemModel;
	}
	protected function setDetailItemModel($value) {
		$this->_detailItemModel = $value;
	}
	
	/**
	 * Returns the node name in the config file that stores the default items. By default this value is the same as the table name
	 * of the owner model.
	 */
	protected function getConfigEntity() {
		if(!isset($this->_configEntity)) {
			$this->_configEntity = $this->owner->tableName();
		}
		return $this->_configEntity;
	}
	protected function setConfigEntity($value) {
		$this->_configEntity = $value;
	}

	/**
	 * Return the name of the item nodes in the config file. This is the detail item model name with 'default' prepended.
	 * E.g. for ContactDetail it will be defaultContactDetail 
	 */
	protected function getConfigItem() {
		if(!isset($this->_configItem)) {
			// Get the class name of the detail item model class
			$detailItemClassName = \parallel\yii\ActiveRecord::model($this->detailItemModel);
			$detailItemClassName = get_class($detailItemClassName);

			// Remove any namespace information if present
			if(strpos($detailItemClassName, "\\") > 0) {
				$this->_configItem = 'default'.substr($detailItemClassName, strrpos($detailItemClassName, "\\")+1);
			} else {
				$this->_configItem = $detailItemClassName;
			}
		}
		return $this->_configItem;
	}

	protected function setConfigItem($value) {
		$this->_configItem = $value;
	}
	
	/**
	 * Returns the field name in the bridge table that points to the entity table. E.g. for CompanyContactDetail it will be company_id
	 * This is read from the model meta data.
	 */
	protected function getBridgeEntityIdField() {
		if(!isset($this->_bridgeEntityIdField)) {
			$bridgeMetaData = \parallel\yii\ActiveRecord::model($this->bridgeItemModel)->metaData;
			$this->_bridgeEntityIdField = $bridgeMetaData->relations[$this->owner->tableName()]->foreignKey;
		}
		return $this->_bridgeEntityIdField;
	}
	
	protected function setBridgeEntityIdField($value) {
		$this->_bridgeEntityIdField = $value;
	}
	
	/**
	 * Returns the field name in the bridge table that points to the detail item table. E.g. for CompanyContactDetail it will be 
	 * contact_detail_id
	 * 
	 * This is read from the model meta data.
	 */
	protected function getBridgeDetailItemIdField() {
		if(!isset($this->_bridgeDetailItemIdField)) {
			$bridgeMetaData = \parallel\yii\ActiveRecord::model($this->bridgeItemModel)->metaData;
			$this->_bridgeDetailItemIdField = $bridgeMetaData->relations[$this->relationChain[1]]->foreignKey; 
		}
		return $this->_bridgeDetailItemIdField;
	}

	protected function setBridgeDetailItemIdField($value) {
		$this->_bridgeDetailItemIdField = $value;
	}
	
	// PRIVATE MEMBERS
	/**
	 * Branch in the config file that stores setting for this entity. E.g. company. This can be set my the owner model, 
	 * but by default it will be the same as the owner model's table name
	 * Access with $this->configEntity
	 */
	private $_configEntity;
	
	/**
	 * Config items that each hold details of the default contact details that should be displayed. E.g. defaultContactDetail
	 * Access with $this->configItem
	 */
	private $_configItem;

	/**
	 * Class name of the model for the bridge table. This can be set my the owner model, or if not set it will be read from 
	 * the relations of the owner model. Code will use bridgeItemModel in order to invoke the getBridgeItemModel menthod which 
	 * will set this parameter. E.g. CompanyContactDetail
	 * Access with $this->bridgeItemModel
	 */
	private $_bridgeItemModel;

	/**
	 * Model class name that represent the detail items. E.g. ContactDetail
	 * Access with $this->detailItemModel.
	 */
	private $_detailItemModel;
	
	/**
	 * Field names of the fields in the bridge table pointing to the entity and the detail item.
	 */
	private $_bridgeEntityIdField;
	private $_bridgeDetailItemIdField;
	
	/**
	 * Holds the AR of all the details items received from the form ($_POST). 
	 * This is set by the setAttributes method 
	 */
	private $_formDetailItems = array();
	private $_formBridgeItems = array();
	
	/**
	 *  Hold the current error status of detail items. Set by validate.
	 */
	private $_formErrors = array();
	
	/**
	 * Items to be saved. Set by validate. Empty items are removed from form arrays. These arrays hold only the items that should be saved
	 * when the save() mehtod is called.
	 */
	private $_saveDetailItems = array();
	private $_saveBridgeItems = array();	
}