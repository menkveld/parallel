<?php
namespace parallel\yii;

/**
 * Parallel system wide base class for CActiveRecord.
 * 
 * Application must not derive from this class directly, but create a local base class in application.components.parallel
 * For example Placement Partner will have PpActiveRecord that is derived from this class in above mentioned directory.
 * 
 * @author Anton Menkveld
 *
 */

class ActiveRecord extends \CActiveRecord {
	const INSTANCE_DATABASE = 1;	// Models will use the instance database by default
	const SYSTEM_DATABASE = 2;
	const DUAL_DATABASE = 3;		// When a model is set to dual database it will look at the global 'useDatabase' parameter
									// to determine which database to use. See components/UserIdentity for an example.

	/**
	 * This function will recursively go through the model and all it's relations and
	 * build up a text representation of the model. This can then be indexed by the search engine.
	 */
	public function getTextContents($excludeRelations = array(), $modelStack = array(), $includeFiles = true) {
		$indent = "";
	
		// Add indentation based on the depth
		$i = count($modelStack);
		while($i > 0) {
			$indent .= "    ";
			$i--;
		}
	
		//$output = $this->modelName.":\n";
		$output = "";
	
		// Add the current model to the model stack
		// to indicate to recursive call to ignore any reference to
		// the current model.
		$modelStack[] = $this->modelName;
	
		// Add all model columns to the text output.
		// Primary Keys are not repeated, as these are typically indexed separately and
		// do not mean anything on their own in any case.
		// Foreign keys are excluded since they will be included with relations
		foreach($this->metaData->columns as $col) {
			if(!$col->isForeignKey && !$col->isPrimaryKey) {
				$output .= $this->{$col->name}."\n";
			}
		}
		// Add relation data recursively
		foreach($this->metaData->relations as $rel) {
			// Skip this relation if it is in the excludeRelations array
			$excludeRelation = false;
			if(array_key_exists($this->modelName, $excludeRelations)) {
				if($excludeRelations[$this->modelName] == $rel->name) {
					$excludeRelation = true;
				}
			}
			
			// Add Belongs To relation only if it is not in the model stack
			// otherwise we will cause an infinite loop
			if(!in_array($rel->className, $modelStack) && !$excludeRelation) {
				// Add Belongs To relations
				if($rel instanceof \CBelongsToRelation) {
					$output .= $indent.$this->{$rel->name}->getTextContents($excludeRelations, $modelStack);
				}

				// Add Has Many relations
				if($rel instanceof \CHasManyRelation) {
					foreach($this->{$rel->name} as $relItem) {
						$output .= $indent.$relItem->getTextContents($excludeRelations, $modelStack);
					}
				}
			}
		}	
		return $output;
	}
		
	/**
	 * This method will recursively go through the model and return it in array format.
	 * 
	 */
	public function getArrayContents() {
		$output = $this->attributes;
		
		foreach($this->relations() as $relation_name => $relation_details) {
			$relation = $this->{$relation_name};
			if($relation instanceof ActiveRecord) {
				$output[$relation_name] = $relation->arrayContents;
			}
		}
		return $output;
	}
	
	/**
	 * Override the getDbConnection method in order to return the
	 * db instance as indicated by the global useSystemDatabase 
	 * parameter. 
	 * 
	 * (non-PHPdoc)
	 * @see CActiveRecord::getDbConnection()
	 */
	public function getDbConnection() {
		switch($this->databaseConnection) {
			case self::SYSTEM_DATABASE :
				return self::getSystemDbConnection();

			case self::INSTANCE_DATABASE :
				return parent::getDbConnection();	
			
			case self::DUAL_DATABASE :
				// Check the global parameter useDatabase to see which database connection to return
				if(\Yii::app()->params['useDatabase']===self::SYSTEM_DATABASE) {  // anything other that SYSTEM_DATABASE will return the instance connection
					return self::getSystemDbConnection();
				} else  {
					return parent::getDbConnection();
				}
		}
	}

	/**
	 * 
	 * Returns a list of validators that needs to be applied to the specified attribute.
	 * The list of validators are read from the validators field in the type table. 
	 * E.g. A ContactDetail has a ContactDetailType associated with it. In the ContactDetailType table, there is a validators field
	 * So when the ContactDetail specifies a certain type, the validators specified for that type will be added and applied to the field.
	 * 
	 * @param array $attribute
	 * @param array $params
	 */
	public function getTypeValidators($attribute, $params) {
		// Check if typeRelation and typeField values are set, if not set to defaults:
		// Type Relation: type
		// Validators Field: validators
		if(!isset($params['typeRelation'])) {
			$params['typeRelation'] = 'type';
		}
		if(!isset($params['validatorsField'])) {
			$params['validatorsField'] = 'validators';
		}

		// Check that the specified relation exists
		if(isset($this->$params['typeRelation'])) {
			$strValidators = $this->{$params['typeRelation']}->{$params['validatorsField']};
		}

		// Create the list of validators if required
		$cListValidators = new \CList;
		if(!empty($strValidators)) {
			// explode the comma separated list to get an array of all required validators
			$arrValidators = explode(',', $strValidators);
			foreach($arrValidators as $validatorName) {
				$validatorName = trim($validatorName);	// remove any space that might be in the comma separated list
				\Yii::trace('Adding validator: '.$validatorName. ' to '.$this->modelName, 'parallel.yii.ActiveRecord');
				// Set validator specific parameters
				switch($validatorName) {
					case 'url' :
						$arrParams = array('defaultScheme' => 'http');
						break;
						
					default :
						$arrParams = array();
				}
				
				// Create the validator object
				$objValidator = \CValidator::createValidator($validatorName, $this, $attribute, $arrParams);
				$cListValidators->add($objValidator->validate($this));
			}
		}
		return $cListValidators;
	}
	
	/**
	 * Allow you to get the model name of any model instance by simply $model->modelName
	 */
	public function getModelName() {
		return get_class($this);
	}

	public function beforeSave() {
		// Timestamp this AR before saving if required
		//$this->timestamp();	// Timestamping replaced by Audit Trail
		
		return parent::beforeSave();
	}
	
	// Protected Members
	/**
	 * This method will timestamp the current record by setting the
	 * last_update and last_update_user fields if they exist.
	 * Some AR's will not have these fields in which case they will be ignored.
	 */
	protected function timestamp() {
		$attributes = $this->attributes;
		if(array_key_exists('last_update', $attributes)) {
			\Yii::trace("Timestamping ".$this->modelName, "parallel.yii.ActiveRecord");
			$this->last_update = date('Y-m-d H:i:s');
			$this->last_update_user = \Yii::app()->user->id;
		}
	}

	/**
	 * 
	 * By default all models will use the instance database, models that need to use the system database
	 * by default should override this parameter and set it to SYSTEM_DATABASE;
	 */
	protected $databaseConnection = self::INSTANCE_DATABASE;
	
	/**
	 * 
	 * Returns a handle to the global system DB
	 */
	protected static function getSystemDbConnection() {
		if (self::$_systemDatabase !== null)
            return self::$_systemDatabase;
        else
        {
            self::$_systemDatabase = \Yii::app()->systemDb;
            if (self::$_systemDatabase instanceof \CDbConnection)
            {
                self::$_systemDatabase->setActive(true);
                return self::$_systemDatabase;
            }
            else
                throw new \CDbException(\Yii::t('yii','Active Record requires a "systemDb" CDbConnection application component.'));
            }		
	}
	
	// Private Members
	/**
	 * Instance of the global system db connection 
	 * @var CDbConnection
	 */
	private static $_systemDatabase;
}