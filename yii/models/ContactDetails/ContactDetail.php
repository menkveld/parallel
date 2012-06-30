<?php
namespace parallel\yii\models\ContactDetails;

/**
 * This is the model class for table "contact_detail".
 *
 * The followings are the available columns in table 'contact_detail':
 * @property string $id
 * @property string $type_id
 * @property string $value
 * @property string $description
 *
 * The followings are the available model relations:
 * @property CompanyContactDetail[] $companyContactDetails
 * @property ContactDetailType $type
 */
class ContactDetail extends \parallel\yii\ActiveRecord
{
	/**
	 * Override the databaseConnection parameter to indicate that this model is a dual database connection model
	 * that can use either the system database or the client database. The global parameter useDatabase will determine 
	 * which database is currently in use. This parameter should be set by the controller when use dual connection models.
	 */
	protected $databaseConnection = self::DUAL_DATABASE;
	
	public function behaviors() {
		return array(
			// Fuzzy Matching
			'match' => array(
					'class' => 'parallel\yii\behaviors\FuzzyMatching',
					'weights' => array(
								'value' => 60,		// For a 100% match both value
								'type_id' => 40,	// and type must match
							),
			),
		);	
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return ContactDetail the static model class
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
		return 'contact_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, value', 'required'),
			array('value', 'length', 'max'=>255),
			array('value', 'getTypeValidators'),  // getTypeValidators will look in the 'type' relation model (ContactDetailType) 
												  // for a field 'validators' and return a list of validators to apply to the value field.
												  // Specify the validators to be applied for each type in the database with a comma
												  // separated list. getTypeValidator is defined in parallel/yii/ActiveRecord
			array('description', 'safe'),

			// Please remove those attributes that should not be searched.
			array('id, type_id, value', 'safe', 'on'=>'search'),
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
			'type' => array(self::BELONGS_TO, 'parallel\yii\models\ContactDetails\ContactDetailType', 'type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'type_id' => 'Type',
			'value' => 'Value',
			'description' => 'Description',
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
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('description',$this->description,true);

		return new \CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * 
	 * Returns all available type options.
	 * Options will be sorted according to the display_order field with largest value shown first.
	 * All types with 0 as display_order will be show last in the list in alphabetical order.
	 */
	public function getTypeOptions() {
		return ContactDetailType::model()->findAll(array('order' => 'display_order DESC, label'));
	}
}