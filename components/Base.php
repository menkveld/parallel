<?php
namespace parallel\components;

/**
 *
 * Base class for the PARALLEL Development Framework
 *
 * @author Anton Menkveld
 *
 */
class Base {
	// Exceptions
	const SET_UNKNOWN_PROPERTY = 1;
	const GET_UNKNOWN_PROPERTY = 2;
	
	// String Constants
	const PROPERTY_NOT_SPECIFIED = "Not Specified";
	
	protected $_properties = array();	// Currently no properties defined by base class
										// Children classes will have to define all required properties.

	/**
	 *
	 * Check if requested value is in the data array if not check the rest of the structure
	 * if requested property is not found, throw an exception
	 *
	 * @param unknown_type $name
	 * @param unknown_type $value
	 * @throws \Exception
	 */
	final public function __set($name, $value) {
		// Check if requested value is in the data if not check the rest of the structure
		// if requested property is not found, throw an exception
		// if a blank value is received, ignore it WHY??? *SOB*

		if(isset($this->_properties[$name])) {
			$this->_properties[$name] = $value;
			return;
		}
		throw new \Exception(__METHOD__.": Unknown Property: '$name'", self::SET_UNKNOWN_PROPERTY);
	}

	/**
	 *
	 * Checks if the requested property exists, if it does
	 * the value is returned, otherwise a exception is thrown.
	 *
	 * @param unknown_type $name
	 * @throws \Exception
	 */
	final public function __get($name) {
		// Check if requested value is in the data if not check the rest of the structure
		// if requested property is not found, throw an exception
		if(array_key_exists($name, $this->_properties)) {
			return $this->_properties[$name];
		}
		throw new \Exception(__METHOD__.": Unknown Property: '$name'", self::GET_UNKNOWN_PROPERTY);
	}

	/**
	 * 
	 * Convert any object of the PARALLEL Framework V2 into text. This is for debug purposes
	 * so that you can simpy say: echo $myObj;
	 */
	final public function __toString() {
		$msg = "<br>------------------------------------------------- <b><i>PARALLEL V2</i></b><br>\n";
		// TODO: Change __CLASS__ to output the name of the actual child class, not only PARALLEL\Base
		$msg .= "<b>Class Name: </b>".get_called_class()."<br><br>\n";
		$msg .= "<b>Properties:</b><br>\n";
		foreach($this->_properties as $idx => $value) {
			if(is_array($value)) {
				$msg .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>$idx: </b>".print_r($value, true)."<br>\n";
			} else {
				$msg .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>$idx: </b>".$value."<br>\n";
			}
		}
		$msg .= "-------------------------------------------------------------------<br>\n";
		return $msg;
	}
	
	/**
	 * 
	 *
	 * @param unknown_type $exception
	 */
	static function userException($exception){
		echo "<br>------------------------------------------------- <b><i>PARALLEL V2</i></b><br>\n";
		echo "<b>UNHANDLED EXCEPTION</b> Please write an exception handler in a try/catch block!!<br>\n";
		echo $exception."<br>\n";
		echo "-------------------------------------------------------------------<br>";
	}
}