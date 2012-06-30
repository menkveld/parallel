<?php
namespace parallel\yii\validators\Addresses;

class AustralianAddress extends \CValidator {
	// SA Mobile: ^((?:\+27|27)|0)(=72|82|73|83|74|84)(\d{7})$
	// SA Numbers: ^(\+27|27|0)[0-9]{2}( |-)?[0-9]{3}( |-)?[0-9]{4}( |-)?(x[0-9]+)?(ext[0-9]+)?
	
	public $pattern = "/[^0-9()+\.-\s]/";
	
	/**
	 * 
	 * This method will do basic checks on telephone numbers and replace all ()-. etc with spaces.
	 * @param unknown_type $object
	 * @param unknown_type $attribute
	 */
	protected function validateAttribute($object,$attribute) {
		// Check that there are no illegal characters in the telephone number.
		// The following is allowed (but might be removed
		// +().- 0-9
		$value = trim($object->$attribute);
		if(preg_match($this->pattern, $value, $matches)) {
			// Illegal characters found
			$message=$this->message!==null?$this->message:\Yii::t('yii','{attribute} contains illegal characters.');
			$this->addError($object,$attribute,$message);
		} else {
			// Only legal characters found, remove all non-digits
			$object->value = preg_replace('/[\W]/', '', $object->$attribute);
		}
	}
}