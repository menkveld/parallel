<?php
namespace parallel\yii\widgets\GlobalSearch;

/**
 * This class defines the action to be called to do a global search. It will return JSON encoded results
 * that can be displayed as required.
 * 
 * This action uses a global search provider to perform the search. The search provider must implement
 * the search provider interface by inherting from the seach provider base class. 
 * 
 * @author Anton Menkveld
 *
 */
class GlobalSearchAutoCompleteAction extends \CAction {

	/**
	 *  
	 */
	public $maxLength = 6;
	
	/*
	 * This function will call the search framework to create the search results
	 * for the global search box.
	 */
	public function run() {
		
		// Setup search parameters
		$params = array(
			'hl' => 'true',
			'hl.fl' => 'body',
			'hl.simple.pre' => "<strong>",
			'hl.simple.post' => '</strong>',
		);
		
		// Search the global index
		$response = \Yii::app()->search->search($_GET['term'], 0, $this->maxLength, $params);
	
		$res = array();
		if ($response->response->numFound > 0) {
			foreach ( $response->response->docs as $doc ) {
				$res_doc = array(
					'model' => $doc->entity_type,
					'name' => $doc->name,
					'description' => "...".$response->highlighting->{$doc->id}->body[0]."...",
					'id' => $doc->entity_id,
					'url' => \Yii::app()->createUrl(strtolower($doc->entity_type).'/default/update', array('id' => $doc->entity_id)),
				);
				$res[] = $res_doc;
			}
		}	

		// Return results to client
		echo \CJSON::encode($res);
        \Yii::app()->end();
	}
}