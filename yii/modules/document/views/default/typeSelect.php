<?php
$this->breadcrumbs=array(
		'Documents'=>array('index'),
		'New',
);
foreach($docTypes as $type) {

	// Set the type_id
	$mode->type_id = $type->id;	
?>
	<?php /** @var BootActiveForm $form */
	$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	    'id'=>'inlineForm',
	    'type'=>'inline',
	    'htmlOptions'=>array('class'=>'well'),
	)); ?>
  	
  	<h2><?php echo $type->label ?></h2>
  	<p><?php echo $type->description ?></p>
	
	<?php echo \CHtml::activeHiddenField($model, 'type_id', array('value' => $type->id)); ?>
	<?php echo $form->textFieldRow($model, 'name'); ?>
	<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'label'=>'Create')); ?>
	 
	<?php $this->endWidget(); ?>

<?php } // foreach $docTypes ?>