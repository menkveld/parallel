<?php
namespace parallel\yii\models\Addresses;

/**
 * This is the model class for table "address".
 *
 * The followings are the available columns in table 'address':
 * @property string $id
 * @property string $type_id
 * @property string $line_1
 * @property string $line_2
 * @property string $line_3
 * @property string $line_4
 * @property string $suburb_label
 * @property string $suburb_postal_code
 *
 * The followings are the available model relations:
 * @property AddressSuburb $suburbLabel
 * @property AddressSuburb $suburbPostalCode
 * @property AddressType $type
 * @property CompanyAddress[] $companyAddresses
 * @property PersonAddress[] $personAddresses
 */
class Address extends \parallel\yii\ActiveRecord
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
							'line_2' => 25,
							'line_3' => 25,
							'suburb_label' => 25,		// For a 100% match both value
							'suburb_postal_code' => 25,	// and type must match
							//Country = 20, // All these should actually be 20 each. We need all 5 things to be the same address
						),
				),
		);
	}
	
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Address the static model class
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
		return 'address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('type_id, line_2, line_3, suburb_label, suburb_postal_code', 'required'),
			array('type_id, suburb_postal_code', 'length', 'max'=>10),
			array('line_1, line_2, line_3, line_4, suburb_label', 'length', 'max'=>255),
				// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, type_id, line_1, line_2, line_3, line_4, suburb_label, suburb_postal_code', 'safe', 'on'=>'search'),
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
			'suburb' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressSuburb', 'suburb_label, suburb_postal_code'),
			'type' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressType', 'type_id'),
			'companyAddresses' => array(self::HAS_MANY, 'CompanyAddress', 'address_id'),
			'personAddresses' => array(self::HAS_MANY, 'PersonAddress', 'address_id'),
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
			'line_1' => 'Unit',
			'line_2' => 'Number',
			'line_3' => 'Street',
			'line_4' => 'Line 4',
			'suburb_label' => 'Suburb',
			'suburb_postal_code' => 'Code',
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
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('line_1',$this->line_1,true);
		$criteria->compare('line_2',$this->line_2,true);
		$criteria->compare('line_3',$this->line_3,true);
		$criteria->compare('line_4',$this->line_4,true);
		$criteria->compare('suburb_label',$this->suburb_label,true);
		$criteria->compare('suburb_postal_code',$this->suburb_postal_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}