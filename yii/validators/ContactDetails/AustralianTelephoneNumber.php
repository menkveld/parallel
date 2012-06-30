<?php
namespace parallel\yii\validators\ContactDetails;

class AustralianTelephoneNumber extends TelephoneNumber {
	
	/**
	 * Regular expression to match all valid Australian telephone numbers
	 */
	public $ausTelPattern = "/(^1300\d{6}$)|(^1800|1900|1902\d{6}$)|(^(0|61|\+61)[2|3|7|8]{1}[0-9]{8}$)|(^13\d{4}$)|(^(0|61|\+61)4\d{2,3}\d{6}$)/";

	/**
	 * This method will validate the given number to be a valid Australian Telephone Number based on the RegEx above.
	 * It will first call on the parent class to do the basic telephone number check.
	 * (non-PHPdoc)
	 * @see parallel\yii\validators.TelephoneNumber::validateAttribute()
	 */
	protected function validateAttribute($object,$attribute) {
		parent::validateAttribute($object, $attribute);

		$value = $object->$attribute;
		if(!preg_match($this->ausTelPattern, $value)) {
			// Not a valid Australian Telephone Number
			$message=$this->message!==null?$this->message:\Yii::t('yii','{attribute} is not a valid Australian telephone number.');
			$this->addError($object,$attribute,$message);
		} else {
			// Passed valid number test, now make it look right
			// If it has 61 - replace with 0
			$value = preg_replace('/61/', '0', $value);
			// If there are not space add spaces as follows:
			// 04xx xxx xxx
			// 08 xxxx xxxx
			if(preg_match('/^04/', $value)) {
				$value = substr($value, 0, 4).' '.substr($value, 4, 3).' '.substr($value, 7);
			}
			if(preg_match('/^0[^4]/', $value)) {
				$value = substr($value, 0, 2).' '.substr($value, 2, 4).' '.substr($value, 6);
			}
			$object->value = $value;
		}
	}
}