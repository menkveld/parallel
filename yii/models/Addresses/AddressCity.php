<?php

/**
 * This is the model class for table "address_city".
 *
 * The followings are the available columns in table 'address_city':
 * @property string $id
 * @property string $label
 * @property string $province_state_id
 * @property string $province_state_region_id
 *
 * The followings are the available model relations:
 * @property AddressProvinceStateRegion $provinceStateRegion
 * @property AddressSuburb[] $addressSuburbs
 */
class AddressCity extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AddressCity the static model class
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
		return 'address_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('label, province_state_id', 'required'),
			array('label', 'length', 'max'=>255),
			array('province_state_id, province_state_region_id', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, label, province_state_id, province_state_region_id', 'safe', 'on'=>'search'),
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
			'provinceStateRegion' => array(self::BELONGS_TO, 'AddressProvinceStateRegion', 'province_state_region_id'),
			'addressSuburbs' => array(self::HAS_MANY, 'AddressSuburb', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'label' => 'Label',
			'province_state_id' => 'Province State',
			'province_state_region_id' => 'Province State Region',
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
		$criteria->compare('label',$this->label,true);
		$criteria->compare('province_state_id',$this->province_state_id,true);
		$criteria->compare('province_state_region_id',$this->province_state_region_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}