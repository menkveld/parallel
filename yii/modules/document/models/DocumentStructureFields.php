<?php

/**
 * This is the model class for table "document_structure_fields".
 *
 * The followings are the available columns in table 'document_structure_fields':
 * @property string $id
 * @property string $document_structure_id
 * @property string $type_id
 * @property string $help_html
 * @property string $help_video_uri
 * @property string $help_document_uri
 * @property string $help_audio_uri
 *
 * The followings are the available model relations:
 * @property DocumentStructure $documentStructure
 */
class DocumentStructureFields extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DocumentStructureFields the static model class
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
		return 'document_structure_fields';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_structure_id', 'required'),
			array('document_structure_id, type_id', 'length', 'max'=>10),
			array('help_video_uri, help_document_uri, help_audio_uri', 'length', 'max'=>255),
			array('help_html, field_size', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_structure_id, type_id, help_html, help_video_uri, help_document_uri, help_audio_uri', 'safe', 'on'=>'search'),
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
			'documentStructure' => array(self::BELONGS_TO, 'DocumentStructure', 'document_structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'document_structure_id' => 'Document Structure',
			'type_id' => 'Type',
			'field_size' => 'Field Size',
			'help_html' => 'Help Html',
			'help_video_uri' => 'Help Video Uri',
			'help_document_uri' => 'Help Document Uri',
			'help_audio_uri' => 'Help Audio Uri',
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
		$criteria->compare('document_structure_id',$this->document_structure_id,true);
		$criteria->compare('type_id',$this->type_id,true);
		$criteria->compare('help_html',$this->help_html,true);
		$criteria->compare('help_video_uri',$this->help_video_uri,true);
		$criteria->compare('help_document_uri',$this->help_document_uri,true);
		$criteria->compare('help_audio_uri',$this->help_audio_uri,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}