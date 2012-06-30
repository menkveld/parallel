<?php
namespace parallel\components\search;

/**
 *
 * Base class for implementation for classes implementing
 * the search engine interface
 *
 * @author Anton Menkveld
 *
 */
class SearchBase extends \parallel\components\Base {

	/**
	 *
	 * This class has a protected contuctor, indicating that if can only be
	 * instansiated by it children classes. This class is merely a base class
	 * for specific inplementations of a search engine. This class does not
	 * implement any search engine specific functionality.
	 */
	protected function __construct($settings) {
		$this->_properties = $settings;

		// Set the host. Default: localhost
		if(array_key_exists('host', $settings)) {
			$this->_properties["host"] = $settings['host'];
		} else {
			$this->_properties["host"] = 'localhost';
		}
		
		// Set the port. Default: 8888
		if(array_key_exists('port', $settings)) {
			$this->_properties["port"] = $settings['port'];
		} else {
			$this->_properties["port"] = '8888';
		}
		
		$this->_properties['api_url'] = $this->host.':'.$this->port;		
		
		// By default the current collection is set to an empty string
		$this->_properties['collection'] = '';
		
		// Indicates the format of the results to be returned
		$this->_properties['result_format'] = 'json';

		// server_timout - the time in seconds after which a call to Solr will timeout
		$this->_properties["server_timeout"] = 4;	// Default 4 seconds		
	}	
}