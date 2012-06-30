<?php
namespace parallel\components\search;

/**
 *
 * This defines the interface that has to be implemented when defining a
 * search engine to be used with the PARALLEL Development Framework
 *
 * @author Anton Menkveld
 *
 */
interface SearchInterface {
	
	/**
	 * Check if the search engine is available.
	 * 
	 */
	public function ping();
	
	/**
	 * Performs a search query with the given text $query on the search engine.
	 * Any additional parameters can be specified in $params.
	 * 
	 * @param string $query
	 * @param int $offset
	 * @param int $limit
	 * @param array $params
	 */
	public function search($query, $offset, $limit, $params);
	
	/**
	 * Add/Updates the current model in the search engine index. 
	 * 
	 * The model will recursively be converted to text. This implies that any related models will also
	 * be converted. If any related models should be excluded, they can be specified with excludeRelations array.
	 * 
	 * @param ActiveRecord $model
	 * @param ActiveRecord $excludeRelations
	 */
	public function indexModel($model, $excludeRelations = array());
	
	/**
	 * Remove the given model from the search index
	 * 
	 * @param ActiveRecord $model
	 */
	public function unindexModel($model);
	
}