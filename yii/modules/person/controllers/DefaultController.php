<?php

class DefaultController extends PpController
{	
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "Person";
	
	/**
	 * Generic CRUD Functionality
	 *
	 * create - Generic entity create action
	 * update - Generic entity update action
	 * delete - Generic entity delete action
	 *
	 */
	public function actions() {
		return array(
				'update' => array(
						'class' => 'parallel\yii\actions\EntityUpdateAction',
						'model' => 'Person',
						'childModels' => array(
								'parallel\yii\models\Addresses\Address' => 'addressItems',
								'parallel\yii\models\ContactDetails\ContactDetail' => 'contactDetailItems',	// Child Model Name => Detail Item Behavior Name (see Company)
						),
				),
	
				'create' => array(
						'class' => 'parallel\yii\actions\EntityUpdateAction',
						'model' => 'Person',
						'childModels' => array(
								'parallel\yii\models\Addresses\Address' => 'addressItems',
								'parallel\yii\models\ContactDetails\ContactDetail' => 'contactDetailItems',	// Child Model Name => Detail Item Behavior Name (see Company)
						),
				),
	
				'delete' => array(
						'class' => 'parallel\yii\actions\EntityDeleteAction',
						'model' => 'Person',
				),
				
				'ajaxAddContactDetail' => array(
						'class' => 'parallel\yii\widgets\ContactDetails\AddContactDetailItemAction',
						'bridgeModel' => 'PersonContactDetail',
				),

				'suburbOptions' => array(
						'class' => 'parallel\yii\zii\widgets\jui\AutoCompleteAction',
						'model' => 'parallel\yii\models\Addresses\AddressSuburb',
						'attributes' => array(
								'label' => 'label',
								'postal_code' => 'postal_code',
								'province_state' => 'province_state_label'
						),
						'order' => 'label',
						'searchType' => parallel\yii\zii\widgets\jui\AutoCompleteAction::SEARCH_FIRST_CHARS
				),
				
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->actionList();
	}
	
	/**
	 * Manages all models.
	 */
	public function actionList()
	{
		if(\Yii::app()->user->checkAccess('Person.view')) {			
			$model=new Person('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Person']))
				$model->attributes=$_GET['Person'];
		
			$this->render('list',array(
					'model'=>$model,
			));
		} else {
			throw new \CHttpException(403, "User does not have sufficient permission for this action.");
		}
			
	}
	
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
				'model'=>$this->loadModel($id),
		));
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='person-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}