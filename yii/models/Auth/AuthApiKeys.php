<?php
namespace parallel\yii\models\Auth;

/**
 * This is the model class for table "auth_api_keys".
 *
 * The followings are the available columns in table 'auth_api_keys':
 * @property string $id
 * @property string $application_name
 * @property string $key
 * @property string $secret
 * @property string $description
 * @property string $date_created
 * @property string $date_exipire
 */
class AuthApiKeys extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return AuthApiKeys the static model class
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
		return 'auth_api_keys';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('application_name, key, secret', 'required'),
			array('application_name', 'length', 'max'=>255),
			array('key, secret', 'length', 'max'=>45),
			array('description, date_created, date_exipire', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, application_name, key, secret, description, date_created, date_exipire', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'application_name' => 'Application Name',
			'key' => 'Key',
			'secret' => 'Secret',
			'description' => 'description',
			'date_created' => 'Date Created',
			'date_exipire' => 'Date Exipire',
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
		$criteria->compare('application_name',$this->application_name,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('secret',$this->secret,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_created',$this->date_created,true);
		$criteria->compare('date_exipire',$this->date_exipire,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}