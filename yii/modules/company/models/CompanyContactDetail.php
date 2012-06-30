<?php

/**
 * This is the model class for table "company_contact_detail".
 *
 * The followings are the available columns in table 'company_contact_detail':
 * @property string $id
 * @property string $company_id
 * @property string $contact_detail_id
 * @property string $label
 * @property string $notes
 *
 * The followings are the available model relations:
 * @property Company $company
 * @property ContactDetail $contactDetail
 */
class CompanyContactDetail extends \parallel\yii\ActiveRecord
{
	/**
	 * Override the databaseConnection parameter to indicate that this model is a dual database connection model
	 * that can use either the system database or the client database. The global parameter useDatabase will determine 
	 * which database is currently in use. This parameter should be set by the controller when use dual connection models.
	 */
	protected $databaseConnection = self::DUAL_DATABASE;
	
	/**
	 * 
	 * Return all the contact details of the specified company.
	 * If no company is specified or if not all default contact detail types
	 * have been recorded, the remaining defaults will also be returned.
	 * 
	 * @param unknown_type $company_id
	 */
	public static function getCompanyContactDetails($companyId) {
		if($companyId!==null) {
			return self::model()->finAll();
		}
	}

	public static function getContactDetailTypes() {
		$types = ContactDetailType::model()->findAll();
		return CHtml::listData($types, 'id', 'label');
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CompanyContactDetail the static model class
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
		return 'company_contact_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, contact_detail_id, label', 'required'),
			array('company_id, contact_detail_id', 'length', 'max'=>10),
			array('label', 'length', 'max'=>255),
			array('notes', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, company_id, contact_detail_id, label, notes', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO, 'Company', 'company_id'),
			'contactDetail' => array(self::BELONGS_TO, 'parallel\yii\models\ContactDetails\ContactDetail', 'contact_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_id' => 'Company',
			'contact_detail_id' => 'Contact Detail',
			'label' => 'Description',
			'notes' => 'Notes',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('contact_detail_id',$this->contact_detail_id,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('notes',$this->notes,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}