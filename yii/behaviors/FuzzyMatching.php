<?php
namespace parallel\yii\behaviors;

/**
 * This behavior adds fuzzy matching to any Active Record. This allows you to create a prototype object
 * and then find simular objects in the database. Each match has a score based on the givin weights.
 * 
 * Weights are assigned to each field that should be compared. E.g. in Person surname might be weighted at 40
 * and mobile phone number weighted at 80. So if a persons surname and mobile phone number matches, his score will be
 * 120. As soon as a score is > 100 it is considered a perfect match.
 * 
 * @author Anton Menkveld
 *
 */
class FuzzyMatching extends \CActiveRecordBehavior {

	/**
	 * Array indicating the search fields and the weight of each field.
	 * This must be set for this behavior to work
	 * 
	 */
	public $weights = array();
	
	/**
	 * Implements fuzzy matching for this AR
	 *
	 * Mathes the current AR record to the database and returns an array of
	 * matched AR's and a score for each. 100 is a perfect match and 0 is
	 * no match.
	 *
	 * Threshold specifies the lowest score item that will be returned. It will
	 * thus look for items that score the threshold value and higher and return them.
	 */	
	public function match($threshold = 100, $returnARs = true) {
		if(empty($this->weights)) {
			return false;
		}
		
		// Validate and normalise the prototype object
		if(!$this->owner->validate()) {
			return false;
		}
		
		$results = array();
		// Query each field and total up the score
		foreach($this->weights as $field => $weight) {
			// Find items matching each field
			$criteria = new \CDbCriteria;
			$criteria->addCondition($field.'=:'.$field);
			$criteria->params = array(':'.$field => $this->owner->{$field});
			$matchingItems = \parallel\yii\ActiveRecord::model($this->owner->modelName)->findAll($criteria);

			foreach($matchingItems as $item) {
				//echo $item->{$field}." ".$item->primaryKey." ".$weight."<br>";			
				@$results[$item->primaryKey]['score'] += $weight;
				if($returnARs) {
					@$results[$item->primaryKey]['item'] = $item;
				}
			}
		}			

		// Remove items with score lower than the threshold
		self::$_threshold = $threshold;
		$results = array_filter($results, 'self::testThreshold');

		return $results;
	}

	// Private Members
	private static $_threshold;
	
	private static function testThreshold($value) {
		return ($value['score'] >= self::$_threshold);
	}
}