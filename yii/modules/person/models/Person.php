<?php
namespace parallel\yii\modules\person\models;

/**
 * This is the model class for table "person".
 *
 * The followings are the available columns in table 'person':
 * @property string $id
 * @property string $full_name
 * @property string $surname
 * @property string $preferred_name
 * @property string $date_of_birth
 * @property string $gender
 *
 * The followings are the available model relations:
 * @property AddressCountry $passportCountry
 * @property PersonAddress[] $personAddresses
 * @property PersonEmployment[] $personEmployments
 * @property PersonQualification[] $personQualifications
 * @property PersonUserRole[] $personUserRoles
 * @property User[] $users
 */
class Person extends \parallel\yii\EntityActiveRecord
{
	// Gender
	const MALE = 1;
	const FEMALE = 2;

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
					'relationChain' => array('contactDetails', 'contactDetail'),	// Person->contactDetails->contactDetail
			),
			
			// Detail Items: Addresses
			'addressItems' => array(
					'class' => 'parallel\yii\behaviors\DetailItems',
					'relationChain' => array('addresses', 'address', 'suburb', 'provinceState', 'country'),	// Person->addresses->address->suburb->province_state->country
					'addDetailItemFields' => array('suburb_label'),		// If a value is entered to suburb_label, the address will be validated and saved
			),
			
			// Automatically index this entity on any update action	
			//'autoIndex' => array(
			//		'class' => 'parallel\yii\behaviors\AutoIndex',
			//		'excludeRelations' => array('parallel\yii\models\ContactDetails\ContactDetail' => 'type'),
			//),

			// Fuzzy Matching
			'match' => array(
					'class' => 'parallel\yii\behaviors\FuzzyMatching',
					'weights' => array(
								'preferred_name' => 40,
								'surname' => 40,
								'date_of_birth' => 30,
								//'contactDetails' => array(),
							),
			),	
			
			// Audit Trail
			//'LoggableBehavior'=> array(
			//		'class' => 'parallel.yii.modules.auditTrail.behaviors.LoggableBehavior',
			//),	
		);
	}
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Person the static model class
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
		return 'person';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('preferred_name, surname', 'required'),
			array('full_name, surname, preferred_name', 'length', 'max'=>255),
			array('gender', 'length', 'max'=>1),
			array('date_of_birth', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, full_name, surname, preferred_name, date_of_birth, gender', 'safe', 'on'=>'search'),
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
			'contactDetails' => array(self::HAS_MANY, '\parallel\yii\modules\person\models\PersonContactDetail', 'person_id', 'on' => 'date_superseded=\'1970-01-01 00:00:00\'', 'order' => 'contact_detail_id'),	
			'addresses' => array(self::HAS_MANY, '\parallel\yii\modules\person\models\PersonAddress', 'person_id', 'on' => 'date_superseded=\'1970-01-01 00:00:00\''),
			//'personEmployments' => array(self::HAS_MANY, 'PersonEmployment', 'person_id'),
			//'personQualifications' => array(self::HAS_MANY, 'PersonQualification', 'person_id'),
			//'personUserRoles' => array(self::HAS_MANY, 'PersonUserRole', 'person_id'),
			//'users' => array(self::HAS_MANY, 'User', 'person_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'full_name' => 'Full Names',
			'surname' => 'Surname',
			'preferred_name' => 'Name',
			'date_of_birth' => 'Date Of Birth',
			'gender' => 'Gender',
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
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('surname',$this->surname,true);
		$criteria->compare('preferred_name',$this->preferred_name,true);
		$criteria->compare('date_of_birth',$this->date_of_birth,true);
		$criteria->compare('gender',$this->gender,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	 * Returns an array of gender options.
	 */
	public function getGenderOptions() {
		return array(
			self::MALE => 'Male',
			self::FEMALE => 'Female',
		);
	}
	
	/**
	 * Returns the text value of the currently selected gender
	 */
	public function getGenderText() {
		$genderOptions=$this->genderOptions;
		if($this->gender === null || $this->gender == 0 || $this->gender > 2) {
			return "Not Specified";
		} else {
			return $genderOptions[$this->gender];
		}
	}
	
	/**
	 * Returns a person's age in as formatted text
	 */
	public function getAgeText() {
		if(isset($this->date_of_birth) && ($this->date_of_birth != '0000-00-00')) {
			$dob = new DateTime($this->date_of_birth);
			$now = new DateTime();
			
			$age = $dob->diff($now);
			
			$ageText = $age->format('%y years');
			if($age->m > 0) {
				if($age->m == 1) {
					$ageText .= ", ".$age->format('%m month');
				} else {	
					$ageText .= ", ".$age->format('%m months');
				}
			}
			if($age->d > 0) {
				if($age->d == 1) {
					$ageText .= ", ".$age->format('%d day');
				} else {
					$ageText .= ", ".$age->format('%d days');
				}
			}
			
			if($age->m == 0 && $age->d == 0) {
				$ageText = "Turns ".$age->y." today";
			}
		} else {
			$ageText = "";
		}
			
		return $ageText;
		
	}

	/**
	 * Returns a name for the person to be displayed on search results.
	 */
	public function getSearchName() {
		return $this->full_name.' '.$this->surname;
	}
	
	/**
	 * Returns a brief description to be displayed on search results.
	 */
	public function getSearchDescription() {
		return $this->preferred_name;
	}
}