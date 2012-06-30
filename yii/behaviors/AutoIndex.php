<?php
namespace parallel\yii\behaviors;

/**
 * Attach this behavior to any model that needs to be indexed for searching. This behavior 
 * will call on the current search engine to index the model after saving. The search engine will
 * typically convert the model (and all related models) to text and then pass to the search engine
 * for indexing.
 * 
 * @author Anton Menkveld
 *
 */
class AutoIndex extends \CActiveRecordBehavior {
	
	/**
	 * Array of models that should be excluded from the indexing process.
	 * E.g. when indexing contact details, we might want to exclude ContactDetailType, 
	 * as it does not add any specific information for contact details.
	 * 
	 * By default the indexing process will recursively index all models related to the 
	 * owner model of this behavior.
	 * 
	 */
	public $excludeRelations = array();

	public function afterSave($event) {
		if($this->searchEngineAvailable()) {
			// Index this model if the search engine is available
			// if not, log a warning
			if(\Yii::app()->search->ping()) {
				\Yii::app()->search->indexModel($this->owner, $this->excludeRelations);
			} else {
				\Yii::log('Search engine not available. Entity not indexed.', 'warning', 'parallel.yii.behaviors.AutoIndex');
			}
		}
		return parent::afterSave($event);
	}
	
	public function afterDelete($event) {
		if($this->searchEngineAvailable()) {
			// Remove this item from the search index
			if(\Yii::app()->search->ping()) {
				\Yii::app()->search->unindexModel($this->owner);
			} else {
				\Yii::log('Search engine not available. Entity not indexed.', 'warning', 'parallel.yii.behaviors.AutoIndex');
			}
		}
		return parent::afterDelete($event);
	}
	
	private function searchEngineAvailable() {
		if(\Yii::app()->search === null) {
			throw \CException('Global search object not initialised. Please create a search object accessible globally with Yii::app()->search to be able to use AutoIndex');
		}
		return true;
	}
}