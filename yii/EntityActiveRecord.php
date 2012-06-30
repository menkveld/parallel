<?php
namespace parallel\yii;

/**
 * This class is the base class for entity models like Company and Person
 * 
 * @author Anton Menkveld
 *
 */
abstract class EntityActiveRecord extends ActiveRecord {
	
	/**
	 * This functions must be implemented by the entity class.
	 * Return the entity name field to be indexed with the entity
	 * 
	 */
	abstract public function getSearchName();
	
	/**
	 * This function must be implemented by the entity class.
	 * Return a text description to be used in search results.
	 */
	abstract public function getSearchDescription();	
}