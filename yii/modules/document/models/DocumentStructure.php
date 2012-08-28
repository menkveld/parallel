<?php

/**
 * This is the model class for table "document_structure".
 *
 * The followings are the available columns in table 'document_structure':
 * @property string $id
 * @property string $document_type_id
 * @property string $name
 * @property string $label
 * @property integer $display_order
 * @property string $parent_id
 *
 * The followings are the available model relations:
 * @property DocumentType $documentType
 * @property DocumentStructure $parent
 * @property DocumentStructure[] $documentStructures
 * @property DocumentStructureFields[] $documentStructureFields
 */
class DocumentStructure extends \parallel\yii\ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return DocumentStructure the static model class
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
		return 'document_structure';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('document_type_id, name, label', 'required'),
			array('display_order', 'numerical', 'integerOnly'=>true),
			array('document_type_id, parent_id', 'length', 'max'=>10),
			array('name, label', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, document_type_id, name, label, display_order, parent_id', 'safe', 'on'=>'search'),
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
			'documentType' => array(self::BELONGS_TO, 'DocumentType', 'document_type_id'),
			'parent' => array(self::BELONGS_TO, 'DocumentStructure', 'parent_id'),
			'documentStructures' => array(self::HAS_MANY, 'DocumentStructure', 'parent_id'),
			'documentStructureFields' => array(self::HAS_MANY, 'DocumentStructureFields', 'document_structure_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'document_type_id' => 'Document Type',
			'name' => 'Name',
			'label' => 'Label',
			'display_order' => 'Display Order',
			'parent_id' => 'Parent',
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
		$criteria->compare('document_type_id',$this->document_type_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('label',$this->label,true);
		$criteria->compare('display_order',$this->display_order);
		$criteria->compare('parent_id',$this->parent_id,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}