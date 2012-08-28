<?php

/**
 * This is the model class for table "company".
 *
 * The followings are the available columns in table 'company':
 * @property string $id
 * @property string $name
 * @property string $short_name
 * @property string $parent_company_id
 *
 * The followings are the available model relations:
 * @property Company $parentCompany
 * @property Company[] $companys
 * @property CompanyAddress[] $companyAddresses
 * @property CompanyAttachement[] $companyAttachements
 * @property CompanyClassification[] $companyClassifications
 * @property CompanyContactDetail[] $companyContactDetails
 * @property CompanyRegistrationNumber[] $companyRegistrationNumbers
 * @property CompanyUserRole[] $companyUserRoles
 * @property PersonEmployment[] $personEmployments
 */
class Company extends \parallel\yii\EntityActiveRecord
{
	/**
	 * Override the databaseConnection parameter to indicate that this model is a dual database connection model
	 * that can use either the system database or the client database. The global parameter useDatabase will determine 
	 * which database is currently in use. This parameter should be set by the controller when use dual connection models.
	 */
	protected $databaseConnection = self::DUAL_DATABASE;
	
	public function behaviors() {
		return array(
			// Entity Icon
			'icon' => array(
				'class' => 'parallel\yii\behaviors\EntityIcon',
			),	
		
			// Ensure that ContactDetail entries are deleted when a company is deleted. Can not be done by DBMS
			'cleanContactDetails' => array(
				'class' => 'parallel\yii\behaviors\CleanOrphanedObjects',	
				'relationChain' => array('contactDetails', 'contactDetail'),	// Company->contactDetails->contactDetail
			),

			// Detail Items: Contact Details
			'contactDetailItems' => array(
				'class' => 'parallel\yii\behaviors\DetailItems',
				'relationChain' => array('contactDetails', 'contactDetail'),	// Company->contactDetails->contactDetail
			),
//
//			'addressItems' => array(
//				'class' => 'parallel\yii\behaviors\DetailItems',
//				'relation' => 'address',
//			),
		
			// Automatically index this entity on any update action	
			'autoIndex' => array(
				'class' => 'parallel\yii\behaviors\AutoIndex',
				'excludeRelations' => array('parallel\yii\models\ContactDetails\ContactDetail' => 'type'),
			),
				
			// Audit Trail
			'LoggableBehavior'=> array(
				'class' => 'parallel.yii.modules.auditTrail.behaviors.LoggableBehavior',
			), 
		);
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Company the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'company';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('short_name', 'required'),
			array('name, short_name', 'length', 'max'=>255),
			array('parent_company_id', 'length', 'max'=>10),
			array('parent_company_id', 'default', 'value' => null),	// This is required to ensure the NULL is sent to the db rather than ''
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, short_name, parent_company_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'parentCompany' => array(self::BELONGS_TO, 'Company', 'parent_company_id'),
			'subsidaryCompanies' => array(self::HAS_MANY, 'Company', 'parent_company_id'),
			//'addresses' => array(self::HAS_MANY, 'CompanyAddress', 'company_id'),
			//'attachements' => array(self::HAS_MANY, 'CompanyAttachement', 'company_id'),
			//'classifications' => array(self::HAS_MANY, 'CompanyClassification', 'company_id'),
			'contactDetails' => array(self::HAS_MANY, 'CompanyContactDetail', 'company_id', 'on' => 'date_superseded=\'1970-01-01 00:00:00\'', 'order' => 'contact_detail_id'),
			//'registrationNumbers' => array(self::HAS_MANY, 'CompanyRegistrationNumber', 'company_id'),
			//'userRoles' => array(self::HAS_MANY, 'CompanyUserRole', 'company_id'),
			//'employees' => array(self::HAS_MANY, 'PersonEmployment', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Official Name',
			'short_name' => 'Name',
			'parent_company_id' => 'Parent Company',
			'contactDetails' => 'Contact Details',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new \CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('short_name',$this->short_name,true);
		$criteria->compare('parent_company_id',$this->parent_company_id,true);

		return new \CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns a name for the company to be displayed on search results.
	 */
	public function getSearchName() {
		return $this->short_name;	
	}

	/**
	 * Returns a brief description to be displayed on search results.
	 */
	public function getSearchDescription() {
		return $this->name;
	}
}