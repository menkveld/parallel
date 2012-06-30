<?php
namespace parallel\components\search;

require_once(\Yii::getPathOfAlias('vendors').'/SolrPhpClient/Apache/Solr/Service.php');

class LucidworksEnterprise extends SearchBase implements SearchInterface {

	// Exceptions
	const CURL_ERROR = 1;
	const JSON_DECODE_ERROR = 2;
	const COLLECTION_NOT_SPECIFIED = 3;
	const COULD_NOT_CREATE_CLIENT = 4;
	
	/**
	 *
	 * The constructor will create the Solr interface object after checking
	 * that all required settings are defined.
	 */
	public function __construct($settings) {
		parent::__construct($settings);
	}

	/**
	 *
	 * This function is used to check if the provider is alive and ready to
	 * provide service. Implementation is not critical, just a useful tool to have sometimes.
	 *
	 * Function returns the Lucidworks version number if successful otherwise false
	 */
	public function version() {
		// cURL interface to LWE server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/api/version"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		$result_json = curl_exec($ch); // run the whole process

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Result from Solr is in XML, translate into PHP object
		$result=json_decode($result_json);
		
		// If the result was OK return true, if not return false
		if($result->lucidworks != null) {
			return $result->lucidworks->version;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns a search engine ID for the given model
	 * 
	 * @param ActiveRecord $model
	 */
	public function getModelId($model) {
		return $model->modelName."_".$model->id;
	}
	
	/**
	 *
	 * This function will return an array of the current collections configures in the LWE server.
	 */
	public function collections() {
		// cURL interface to LWE server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/api/collections"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		$result_json = curl_exec($ch); // run the whole process

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Result from Solr is in XML, translate into PHP object
		if($result = json_decode($result_json)) {
			$collections = array();
			foreach($result as $collection) {
				$collections[] = $collection->name;
			}
			return $collections;
		} else {
			throw new \Exception(__METHOD__.": Could not decode returned JSON.", self::JSON_DECODE_ERROR);
		}
	}
	
	public function search($query, $offset = 0, $limit = 10, $params = array()) {
		// In this case we simply call the search method of the client API
		// Search is required by the interface, so we have to do this
		return $this->solrClient()->search($query, $offset, $limit, $params);
	}

	/**
	 * This method converts a standard Active Record model into the format as expected 
	 * by the search engine for indexing. In this case it will return an Apache_Solr_Document instance.
	 * 
	 * @param parallel\yii\ActiveRecord $model
	 */
	public function formatModel($model, $excludeRelations = array()) {
		// Create the document object
		$doc = new \Apache_Solr_Document();
		
		// Unique ID
		$doc->id = $this->getModelId($model);
		
		// Entity Type
		$doc->entity_type = $model->modelName;
		$doc->entity_id = $model->id;
		$doc->name = $model->searchName;
		$doc->description = $model->searchDescription;
		//$doc->keywords = $model->keywords;
		$doc->body = $model->getTextContents($excludeRelations);

		return $doc;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see parallel\components\search.SearchInterface::indexModel()
	 */
	public function indexModel($model, $excludeRelations = array()) {
		$doc = $this->formatModel($model, $excludeRelations);
    	$this->solrClient()->addDocument($doc);
    	$this->solrClient()->commit();
    	$this->solrClient()->optimize(false, false);	// Optimize asynchronously to increase speed. In future might need to schedule event to optimise.
	}
	
	/**
	 * (non-PHPdoc)
	 * @see parallel\components\search.SearchInterface::unindexModel()
	 */
	public function unindexModel($model) {
		$this->solrClient()->deleteById($this->getModelId($model));		
    	$this->solrClient()->commit();
    	$this->solrClient()->optimize(false, false);
	}
	
	/**
	 * Check that the search engine is available.
	 * 
	 * Ping is explicitely required by SearchInterface, so has to be specified explicitely.
	 */
	public function ping() {
		return $this->solrClient()->ping();
	}
	
	/**
	 * Expose the functions of Apache_Solr_Service
	 * 
	 */
	public function __call($method, $params)
	{
		// Call the function on the client object
		return call_user_func_array(array($this->solrClient(), $method), $params);
	}

	// Protected Members
	/**
	 *
	 * This function will return the instance of the solr client object used to interact with the 
	 * solr server.
	 * 
	 * @throws \Exception
	 */
	protected function solrClient() {
		// Chech that the collection has been specifed before Solr functions are called.
		if($this->collection == '') {
			throw new \Exception(__METHOD__.": Collection not specified. Set the 'collection' property before calling Solr functions.", self::COLLECTION_NOT_SPECIFIED);
		}

		// Create an instance of the Apache Solr Client if it does not exist
		if(!isset($this->_solrClient)) {
			$this->_solrClient = new \Apache_Solr_Service(
										$this->host,
										$this->port,
										'/solr/'.$this->collection
									);
		}
		if($this->_solrClient!==null) {
			return $this->_solrClient;
		} else {
			throw new \Exception(__METHOD__.": Could not SolrClient object.", self::COULD_NOT_CREATE_CLIENT);
		}
	}

	// Private Members
	/**
	 * 
	 * Instance of the Apache Solr PHP client library.
	 * DO NOT ACCESS THIS DIRECTLY, use $this->solrClient()
	 */
	private $_solrClient;	
}