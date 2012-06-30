<?php
/**
 * Parallel system wide base class for CController.
 * 
 * Application must not derive from this class directly, but create a local base class in application.components.parallel
 * For example Placement Partner will have PpController in above mentioned directory that is derived from this class.
 * 
 * @author Anton Menkveld
 *
 */
namespace parallel\yii;

class Controller extends \CController {
	
	/**
	 * To be able to use the generic loadModel method, child classes should 
	 * ovveride this property. 
	 */
	protected $_model;

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) {
		if(empty($this->_model)) {
			throw new \CException('Child class must override the _model property.');
		} else {
			$model=\parallel\yii\ActiveRecord::model($this->_model)->findByPk($id);
			if($model===null)
				throw new \CHttpException(404,'The requested page does not exist.');
			return $model;
		}
	}
	
}