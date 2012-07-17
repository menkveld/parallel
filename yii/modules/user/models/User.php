<?php
namespace parallel\yii\modules\user\models;

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $person_id
 * @property string $username_contact_detail_id
 * @property string $password
 *
 * The followings are the available model relations:
 * @property Person $person
 * @property ContactDetail $usernameContactDetail
 */
class User extends \parallel\yii\EntityActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('person_id, username_contact_detail_id, password', 'required'),
			array('person_id, username_contact_detail_id', 'length', 'max'=>10),
			array('password', 'length', 'max'=>50),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, person_id, username_contact_detail_id, password', 'safe', 'on'=>'search'),
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
			'person' => array(self::BELONGS_TO, '\parallel\yii\modules\person\models\Person', 'person_id'),
			'usernameContactDetail' => array(self::BELONGS_TO, '\parallel\yii\models\ContactDetails\ContactDetail', 'username_contact_detail_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'person_id' => 'Person',
			'username_contact_detail_id' => 'Email',
			'password' => 'Password',
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
		$criteria->compare('person_id',$this->person_id,true);
		$criteria->compare('username_contact_detail_id',$this->username_contact_detail_id,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Method used to encrypt password. Currntly simply MD5 but could be more complicated in future.
	 * 
	 * @param unknown_type $password
	 */
	public function encryptPassword($password) {
		return MD5($password);
	}
	
	/**
	 * Returns a name for the person to be displayed on search results.
	 */
	public function getSearchName() {
		return $this->username;
	}
	
	/**
	 * Returns a brief description to be displayed on search results.
	 */
	public function getSearchDescription() {
		return $this->username;
	}	
}