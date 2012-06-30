<?php
namespace parallel\yii\models\Addresses;

/**
 * This is the model class for table "address_province_state".
 *
 * The followings are the available columns in table 'address_province_state':
 * @property string $label
 * @property string $country_iso_code
 * @property string $code
 *
 * The followings are the available model relations:
 * @property AddressCountry $countryIsoCode
 * @property AddressSuburb[] $addressSuburbs
 */
class AddressProvinceState extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AddressProvinceState the static model class
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
		return 'address_province_state';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, country_iso_code, code', 'required'),
			array('label', 'length', 'max'=>255),
			array('country_iso_code', 'length', 'max'=>2),
			array('code', 'length', 'max'=>5),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('label, country_iso_code, code', 'safe', 'on'=>'search'),
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
			'country' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressCountry', 'country_iso_code'),
			'addressSuburbs' => array(self::HAS_MANY, 'AddressSuburb', 'province_state_label'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'label' => 'Label',
			'country_iso_code' => 'Country Iso Code',
			'code' => 'Code',
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

		$criteria->compare('label',$this->label,true);
		$criteria->compare('country_iso_code',$this->country_iso_code,true);
		$criteria->compare('code',$this->code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}