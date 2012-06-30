<?php
class DefaultController extends PpController
{
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "Company";
	
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
				'create' => array(
						'class' => 'parallel\yii\actions\EntityUpdateAction',
						'model' => 'Company',
						'childModels' => array(
								'parallel\yii\models\ContactDetails\ContactDetail' => 'contactDetailItems',	// Child Model Name => Detail Item Behavior Name (see Company)
						),
				),
				
				'view' => array(
						'class' => 'parallel\yii\actions\EntityViewAction',
						'model' => 'Company',
				),
				
				'update' => array(
						'class' => 'parallel\yii\actions\EntityUpdateAction',
						'model' => 'Company',
						'childModels' => array(
								'parallel\yii\models\ContactDetails\ContactDetail' => 'contactDetailItems',	// Child Model Name => Detail Item Behavior Name (see Company)
						),
				),
	
				'delete' => array(
						'class' => 'parallel\yii\actions\EntityDeleteAction',
						'model' => 'Company',
				),
				
				'ajaxAddContactDetail' => array(
						'class' => 'parallel\yii\widgets\ContactDetails\AddContactDetailItemAction',
						'bridgeModel' => 'CompanyContactDetail',
				),

				'parentOptions' => array(
						'class' => 'parallel\yii\zii\widgets\jui\AutoCompleteAction',
						'model' => 'Company',
						'attributes' => array(
								'label' => 'short_name',
								'value' => 'short_name',
								'id' => 'id',
								'detail' => 'name',
						),
						'order' => 'short_name',
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
		// Company.view right is required to list companies
		if(\Yii::app()->user->checkAccess('Company.view')) {
			$model=new Company('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Company']))
				$model->attributes=$_GET['Company'];
			
			$this->render('list',array(
					'model'=>$model,
			));
		} else {
			throw new \CHttpException(403, "User does not have sufficient permission for this action.");
		}
	}
}