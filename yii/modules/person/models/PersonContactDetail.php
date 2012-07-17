<?php
namespace parallel\yii\modules\person\models;

/**
 * This is the model class for table "person_contact_detail".
 *
 * The followings are the available columns in table 'person_contact_detail':
 * @property string $id
 * @property string $person_id
 * @property string $contact_detail_id
 * @property string $label
 * @property string $notes
 * @property string $date_superseded
 *
 * The followings are the available model relations:
 * @property Person $person
 * @property ContactDetail $contactDetail
 */
class PersonContactDetail extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PersonContactDetail the static model class
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
		return 'person_contact_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('person_id, contact_detail_id, label', 'required'),
			array('person_id, contact_detail_id', 'length', 'max'=>10),
			array('label', 'length', 'max'=>255),
			array('notes, date_superseded', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, person_id, contact_detail_id, label, notes, date_superseded', 'safe', 'on'=>'search'),
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
			'person' => array(self::BELONGS_TO, 'Person', 'person_id'),
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
			'person_id' => 'Person',
			'contact_detail_id' => 'Contact Detail',
			'label' => 'Label',
			'notes' => 'Notes',
			'date_superseded' => 'Date Superseded',
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
		$criteria->compare('contact_detail_id',$this->contact_detail_id,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('notes',$this->notes,true);
		$criteria->compare('date_superseded',$this->date_superseded,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}