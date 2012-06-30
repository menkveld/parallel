<?php
namespace parallel\components\search;

class Solr extends SearchBase implements SearchInterface {

	/**
	 *
	 * The constructor will create the Solr interface object after checking
	 * that all required settings are defined.
	 */
	public function __construct($settings) {
		parent::__construct($settings);

		// Define all properties specific to this class
		// Properties not defined here will cause an exception when set.
		// Once properties are defined here they can be accessed like: $this->property;

		// auto_commit indicates that the document is automacally
		// commited after being indexed. Switch this off and explicitly
		// commitDocuments() after indexing a larger volume of documents.
		$this->_properties["auto_commit"] = true;
	}

	/**
	 *
	 * This function is used to check if the provider is alive and ready to
	 * provide service. Implementation is not critical, just a useful tool to have sometimes.
	 *
	 * It will simply return true if all is good and false if there is an error
	 */
	public function ping() {
		//$this->checkCollection();
		
		// cURL interface to Solr server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/solr/".$this->collection."/admin/ping"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		$result_xml = curl_exec($ch); // run the whole process

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Result from Solr is in XML, translate into PHP object
		$result=simplexml_load_string($result_xml);
		// If the result was OK return true, if not return false
		if($result->str == "OK") {
			return true;
		} else {
			return false;
		}
	}

	public function search($query, $offset = 0, $limit = 10, $params = array()) {
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/solr/".$this->collection."/select?q=$query&wt=".$this->result_format); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		$result = curl_exec($ch);

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Return results
		return $result;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see PARALLEL.Text_Search_Interface::extractContents()
	 *
	 * The implementation of this function in Solr, simply uses Solr to
	 * extract and return document content. This function does not need to be
	 * called before a document can be indexed as Solr will automatically
	 * extract the document content before indexing.
	 */
	public function extractContents($url) {

		if(file_exists($url)) {
			$post_data = array(
				"extractOnly" => "true", 
				"extractFormat" => $this->result_format,
				"file" => "@$url"
			);
		} else {
			throw new \Exception(__METHOD__.": File does not exist: ".$url, self::FILE_DOES_NOT_EXIST);
		}

		// cURL interface to Solr server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/update/extract"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		curl_setopt($ch, CURLOPT_POST, 1); // set POST method
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
		$result_xml = curl_exec($ch);

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// If text contents is requested, only send the content, no other fields
		// If XML is requested, send the whole lot
		if($this->result_format == "text") {
			$result = simplexml_load_string($result_xml);
			return $result->str;
		}
		if($this->result_format == "xml") {
			return $result_xml;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PARALLEL.Text_Search_Interface::indexDocument()
	 */
	public function indexDocument($fields, $url) {
		// Add literal. to each field name in the fields array
		foreach($fields as $name => $value) {
			$post_data["literal.".$name] = $value;
		}

		// Auto commit
		if($this->_properties["auto_commit"]) {
			$post_data["commit"] = "true";
		} else {
			$post_data["commit"] = "false";
		}

		// File
		// Check that the file exists, If not, throw exception
		if(file_exists($url)) {
			$post_data["file"] = "@$url";
		} else {
			throw new \Exception(__METHOD__.": File does not exist: ".$url, self::FILE_DOES_NOT_EXIST);
		}

		// cURL interface to Solr server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/update/extract"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		curl_setopt($ch, CURLOPT_POST, 1); // set POST method
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); // add POST fields
		$result_xml = curl_exec($ch);

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Return results
		if($result = simplexml_load_string($result_xml)) {
			return true;
		} else {
			// TODO: Throw an exception with some indication of what went wrong
			return false;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see PARALLEL.Text_Search_Interface::deleteDocument()
	 */
	public function deleteDocument($doc_id) {

	}

	/**
	 *
	 * This function will commit the documents
	 * indexed (without auto commmit) since the last
	 * commit.
	 */
	public function commitDocuments() {
		// cURL interface to Solr server
		$ch = curl_init();    // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$this->api_url."/update?commit=true"); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, $this->server_timeout); // times out after 4s
		$result_xml = curl_exec($ch);

		// Check for cURL errors
		if($err_no = curl_errno($ch)) {
			throw new \Exception(__METHOD__.": cURL retuned Error No: ".$err_no, self::CURL_ERROR);
		} else {
			curl_close($ch);
		}

		// Result from Solr is in XML, translate into PHP object
		$result = simplexml_load_string($result_xml);

		// If the result was OK return true, if not return false
		if($result->str == "OK") {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * Does a quick sanity check on the current settings to ensure everything is set
	 */
	private function checkCollection() {
		if(empty($this->collection)) {
			throw new \Exception(__METHOD__.": collection not specified: ", self::COLLECTION_NOT_SPECIFIED);
		}
	}
}