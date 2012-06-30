<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'person-form',
	//'enableAjaxValidation'=>true,
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'preferred_name'); ?>
		<?php echo $form->textField($model,'preferred_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'preferred_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'surname'); ?>
		<?php echo $form->textField($model,'surname',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'surname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'full_name'); ?>
		<?php echo $form->textField($model,'full_name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'full_name'); ?>
	</div>
	
	<div class="row">
		<div style="display: inline-block;">
			<?php echo $form->labelEx($model,'date_of_birth'); ?>
			<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
						'model' => $model,
					    'attribute'=>'date_of_birth',
					    // additional javascript options for the date picker plugin
					    'options'=>array(
							'changeYear'=>true,
							'yearRange'=>'-70:-12',
							'changeMonth'=>true,
						),
				));		
			?>
			<?php echo $form->error($model,'date_of_birth'); ?>
		</div>
		<div style="display: inline-block; margin-left: 60px;">
			<?php echo $form->labelEx($model,'gender'); ?>
			<div class="compactRadioGroup">
				<?php echo $form->radioButtonList($model,'gender', $model->genderOptions, array('separator' => '&nbsp;&nbsp;&nbsp;')); ?>
			</div>
			<?php echo $form->error($model,'gender'); ?>
		</div>
	</div>

	&nbsp;
	<div class="row">
		<div class="portlet-content">
		<?php echo $form->labelEx($model,'addresses'); ?>
		<?php 
			$this->widget('parallel\yii\widgets\Addresses\Addresses',
				array(
					'model' => $model,
				)
			);
		?>
		</div>
	</div>	
	
	&nbsp;
	<div class="row">
		<div class="portlet-content">
		<?php echo $form->labelEx($model,'contactDetails'); ?>
		<?php 
			$this->widget('parallel\yii\widgets\ContactDetails\ContactDetails',
				array(
					'model' => $model,
				)
			);
		?>
		</div>
	</div>
	
	<div class="row buttons">
		<?php $this->widget('zii.widgets.jui.CJuiButton', 
				array(
					'name' => 'submit',
					'caption' => $model->isNewRecord ? 'Create' : 'Save',
			));?>	
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->