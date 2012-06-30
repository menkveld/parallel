<?php
namespace parallel\yii\zii\widgets\jui;

/**
 * 
 * This class defines an action that can be used with any controller
 * to provide auto complete data for the AutoCompleteWidget.
 * 
 * @author Anton Menkveld
 *
 */
class AutoCompleteAction extends \CAction {
	
	const SEARCH_EXACT = 1;			// Search string must be an exact match
	const SEARCH_FIRST_CHARS = 2;	// First characters of the search string must match. A wildcard % is added to the back of the search string
	const SEARCH_IN_STRING = 3;		// The search string appears somewhere in the field value
	
    public $model;
    public $attributes = array();
    public $order;
    public $searchType = self::SEARCH_IN_STRING;
    
    private $_results = array();
     
    public function run()
    {
        if(isset($this->model) && isset($this->attributes)) {
            $criteria = new \CDbCriteria();
            
            switch($this->searchType) {
            	case self::SEARCH_EXACT:
            		$criteria->compare($this->attributes['label'], $_GET['term'], false);
            		break;
            		
            	case self::SEARCH_FIRST_CHARS:
            		$criteria->compare($this->attributes['label'], $_GET['term']."%", true, 'AND', false);
            		break;
            	
            	default:
            		$criteria->compare($this->attributes['label'], $_GET['term'], true);
            		
            }
            $criteria->order = $this->order;
            $model = new $this->model;
            foreach($model->findAll($criteria) as $m)
            {
            	$attribs = array();
            	foreach($this->attributes as $key => $value) {
            		$attribs[$key] = $m->{$value};
            	}
            	
                $this->_results[] = $attribs;
            }
 
        }
        echo \CJSON::encode($this->_results);
        \Yii::app()->end();
    }
}