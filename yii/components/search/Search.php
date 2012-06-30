<?php
namespace parallel\yii\search;

/**
 * The search component provides a globally accessible component to do searches.
 * 
 * Seaching involves querying a global index which returns a model name and id for all items that match the criteria.
 * 
 * The search component will use a seach provider to do the actual searching. This is typically 
 * an interface to an index service of some sort.
 *  
 * @author Anton Menkveld
 *
 */
class Search extends \CApplicationComponent {
	
	/**
	 * 
	 * The class that implements SearchInterface that should be used to provide search functionality
	 */
	public $searchEngine = '\parallel\components\search\LucidworksEnterprise';

	/**
	 * 
	 * The host name or IP address of the search engine host
	 * Default: localhost
	 */
	public $host = 'localhost';
	
	/**
	 * 
	 * The port number on the search engine host
	 * Default: 8888
	 */
	public $port = '8888';
		
	public function init() {
		// Create search engine settings array
		$searchEngineSettings = 
			array(
				'host' => $this->host,
				'port' => $this->port,		
			);
		
		// Check that the specified search engine class implements parallel\components\search\SearchInterface
		$interface = class_implements($this->searchEngine);
		$interface = array_search('parallel\components\search\SearchInterface', $interface);
		if($interface == 'parallel\components\search\SearchInterface') {
			$this->_engine = new $this->searchEngine($searchEngineSettings);
		} else {
			throw new \CException(\Yii::t('Search', 'Given search engine class ('.$this->searchEngine.') does not implement parallel\components\search\SearchInterface'));
		}
	}
		
	/**
	* Call search engine methods directly on the _engine object. If methods are defined in this class
	* and in the search engine object, local methods will be called e.g. search()
	*
	* @return string
	*/
	public function __call($method, $params) {
		if (is_object($this->_engine)) { 
			return call_user_func_array(array($this->_engine, $method), $params);
		} else {
			throw new \CException(\Yii::t('Search', 'Can not call a method of a non existent object'));	
		}
	}
	
	public function setCollection($collection) {
		$this->_engine->collection = $collection;
	}
	
	public function getCollection() {
		return $this->_engine->collection;
	}
	
	// Private Members
	private $_engine;
}