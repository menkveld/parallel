<?php
/**
 * System wide base class for CController.
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
	 * @var string the default layout for the controller view. Defaults to '//layouts/column2',
	 * meaning using a content and sidebar layout. See column2.php under current theme
	 */
	public $layout='//layouts/column2';
	
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
				'accessControl', // perform access control for CRUD operations
		);
	}
	
	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
				array('allow',
						'users'=>array('@'),
				),
				array('deny',  // deny all unauthenticated users
						'users'=>array('*'),
				),
		);
	}

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
	
	/**
	 * To be able to use the generic loadModel method, child classes should 
	 * ovveride this property. 
	 */
	protected $_model;	
}