<?php

class RestController extends \parallel\yii\controllers\RestController{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	//protected $_model = "parallel\yii\modules\company\models\Company";
	
	public function actionUpdate() {
		if($this->verifyPath($_REQUEST['id'], $_REQUEST['path'])) {
			$item = DocumentContent::model()->findByPk($_REQUEST['path']);
		
			if($item===null) {
				$item = new DocumentContent();
			}
			
			$item->key = $_REQUEST['path'];
			$item->value = "";
			$item->save();
			
			// Return success result.
		} else {
			// Send error - Resource not found
		}
	}
	
	public function actionRead() {
		if($this->verifyPath($_REQUEST['id'], $_REQUEST['path'])) {
			$item = DocumentContent::model()->findByPk($_REQUEST['path']);
		
			if($item===null) {
				// Return content item
			} else {
				// Return empty result
			} 
		}
	}
	
	/**
	 * Override the bulk update function to disable it for this resource.
	 */
	public function actionBulkUpdate() {
		echo "Method not allowed";
	}
	
	/**
	 * This private method is used to verify the validity of the given path. This is for security purposes
	 * and to ensure that the database is not filled with illegal entries.
	 * 
	 * This function will check both that each element of the path exists and that the parent of each item
	 * is is correct. 
	 * 
	 * @param $path
	 */
	private function verifyPath($document_id, $path) {
		$document = Document::model()->findByPk($document_id);
		$structures = $document->type->documentStructures;
		
		// Create an array of the structures, indexed by the structure name
		$arrStructures = array();
		foreach($structures as $structure) {
			$arrStructures[$structure->name] = $structure;
		}
		
		$arrPath = array_reverse(explode("/", $path));
		$prevItem = "";
		foreach ($arrPath as $item) {
			if(array_key_exists($item, $arrStructures)) {
				// Check that this item is the parent of the previous item
				if(!empty($prevItem)) {
					if($prevItem->parent->name != $item) {
						return false;
					}					
				}
				// Set this structure item as the previous item for the next round of checks.
				$prevItem = $arrStructures[$item];
			} else {
				return false;
			}
		}	// foreach $arrPath
		
		// If we got this far, result is true
		return true;
	}
}