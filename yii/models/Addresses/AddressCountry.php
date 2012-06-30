<?php
namespace parallel\yii\models\Addresses;

/**
 * This is the model class for table "address_country".
 *
 * The followings are the available columns in table 'address_country':
 * @property string $iso_code
 * @property string $label
 * @property string $dailing_code
 * @property string $iso_code_3
 * @property integer $iso_code_num
 * @property string $currency_code
 * @property string $currency_symbol
 * @property string $default_language_code
 *
 * The followings are the available model relations:
 * @property AddressProvinceState[] $addressProvinceStates
 */
class AddressCountry extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AddressCountry the static model class
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
		return 'address_country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iso_code, label', 'required'),
			array('iso_code_num', 'numerical', 'integerOnly'=>true),
			array('iso_code', 'length', 'max'=>2),
			array('label', 'length', 'max'=>255),
			array('dailing_code, currency_symbol', 'length', 'max'=>5),
			array('iso_code_3, currency_code', 'length', 'max'=>3),
			array('default_language_code', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('iso_code, label, dailing_code, iso_code_3, iso_code_num, currency_code, currency_symbol, default_language_code', 'safe', 'on'=>'search'),
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
			'addressProvinceStates' => array(self::HAS_MANY, 'AddressProvinceState', 'country_iso_code'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'iso_code' => 'Iso Code',
			'label' => 'Label',
			'dailing_code' => 'Dailing Code',
			'iso_code_3' => 'Iso Code 3',
			'iso_code_num' => 'Iso Code Num',
			'currency_code' => 'Currency Code',
			'currency_symbol' => 'Currency Symbol',
			'default_language_code' => 'Default Language Code',
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

		$criteria->compare('iso_code',$this->iso_code,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('dailing_code',$this->dailing_code,true);
		$criteria->compare('iso_code_3',$this->iso_code_3,true);
		$criteria->compare('iso_code_num',$this->iso_code_num);
		$criteria->compare('currency_code',$this->currency_code,true);
		$criteria->compare('currency_symbol',$this->currency_symbol,true);
		$criteria->compare('default_language_code',$this->default_language_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}