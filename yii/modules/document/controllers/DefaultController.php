<?php
class DefaultController extends PpController
{
	// Render document manager in 1 column layout by default
	public $layout = '//layouts/column1';	// Render the view without the navigation bar
	
	// Overide the model property of the Controller base class
	// Once this is done, the generic loadModel method can be used.
	protected $_model = "Document";
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->actionList();
	}
	
	/**
	 * Action to allow the user to select a document type and then create a new document
	 * of the given type and redirect the user to the populate page once the document has been created.
	 */
	public function actionCreate() {
		$model=new Document;
		if(isset($_POST['Document']))
		{
			// Create a document of the selected type and then redirect to the populate page
			$model->attributes=$_POST['Document'];
			if($model->save())
				$this->actionPopulate($model->id);
		} else {	
			// Document details not provided, first render the document type selection page
			$docTypes = DocumentType::model()->findAll();
			$this->render('typeSelect',array(
				'model' => $model,
				'docTypes'=>$docTypes,
			));
		}
	}
	
	/**
	 * Manages all models.
	 */
	public function actionList()
	{
	}
	
	public function actionPopulate($id) {
		// Get the requested document from the database
		// Render the document population form
		$document = $this->loadModel($id);
		$structure = DocumentStructure::model()->findAll(
			array(
				//'select'=>'title',
				'condition'=>'document_type_id = :typeID AND parent_id is null',
				'params'=>array(':typeID'=>$document->type_id),
		));
				
		// Render the document populate form
		$this->render('populate', array(
				'document' => $document,
				'structure' => $structure,	
			));
	}
	
	/**
	 * Create a full document path for the given field ID.
	 * Currently the field will be read from the database, but this should be changed in 
	 * future so that the field data is written to cache when the form is created and then 
	 * simply read from cache here. Field layouts will not change during document population.
	 * 
	 * @param unknown_type $field_id
	 */
	protected function createFieldPath($path, $fieldType = 'text') {
		$result = implode("/", $path);
		$result .= "/".$fieldType;
		return $result;
	}
}