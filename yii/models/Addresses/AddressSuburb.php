<?php
namespace parallel\yii\models\Addresses;

/**
 * This is the model class for table "address_suburb".
 *
 * The followings are the available columns in table 'address_suburb':
 * @property string $label
 * @property string $postal_code
 * @property string $province_state_label
 * @property string $city_id
 * @property string $city_region_id
 *
 * The followings are the available model relations:
 * @property Address[] $addresses
 * @property Address[] $addresses1
 * @property AddressCity $city
 * @property AddressCityRegion $cityRegion
 * @property AddressProvinceState $provinceStateLabel
 */
class AddressSuburb extends \parallel\yii\ActiveRecord 
{	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AddressSuburb the static model class
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
		return 'address_suburb';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, postal_code, province_state_label', 'required'),
			array('label, province_state_label', 'length', 'max'=>255),
			array('postal_code, city_id, city_region_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('label, postal_code, province_state_label, city_id, city_region_id', 'safe', 'on'=>'search'),
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
			'addresses' => array(self::HAS_MANY, 'parallel\yii\models\Addresses\Address', 'suburb_label, suburb_postal_code'),
			'city' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressCity', 'city_id'),
			'cityRegion' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressCityRegion', 'city_region_id'),
			'provinceState' => array(self::BELONGS_TO, 'parallel\yii\models\Addresses\AddressProvinceState', 'province_state_label'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'label' => 'Label',
			'postal_code' => 'Postal Code',
			'province_state_label' => 'State',
			'city_id' => 'City',
			'city_region_id' => 'City Region',
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
		$criteria->compare('postal_code',$this->postal_code,true);
		$criteria->compare('province_state_label',$this->province_state_label,true);
		$criteria->compare('city_id',$this->city_id,true);
		$criteria->compare('city_region_id',$this->city_region_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}