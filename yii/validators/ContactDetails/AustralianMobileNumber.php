<?php
namespace parallel\yii\validators\ContactDetails;

class AustralianMobileNumber extends AustralianTelephoneNumber {
	public $ausMobilePattern = '/^(0|61|\+61)4\d{2,3}\d{6}$/';
	
	/**
	 * Validate given number as a valid Australian mobile phone number.
	 * 
	 * (non-PHPdoc)
	 * @see parallel\yii\validators.AustralianTelephoneNumber::validateAttribute()
	 */
	protected function validateAttribute($object,$attribute) {
		parent::validateAttribute($object, $attribute);
		
		$value = $object->$attribute;
		
		// Remove any spacing that AustralianTelephoneNumber might have added
		$value = preg_replace('/[\W]/', '', $value);
		if(!preg_match($this->ausMobilePattern, $value)) {
			// Not a valid Australian Telephone Number
			$message=$this->message!==null?$this->message:\Yii::t('yii','{attribute} is not a valid Australian mobile number.');
			$this->addError($object,$attribute,$message);
		} else {
			// Number is valid - add standard mobile number spacing
			$value = substr($value, 0, 4).' '.substr($value, 4, 3).' '.substr($value, 7);
		}
	}
}