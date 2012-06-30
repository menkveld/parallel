<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'company-form',
	//'enableAjaxValidation'=>true,
	//'enableClientValidation'=>true,
)); ?>
	<div class="row">
		<?php echo $form->labelEx($model,'short_name'); ?>
		<?php $this->widget('parallel\yii\zii\widgets\jui\JuiAutoComplete',
					array(
						'attribute' => 'short_name',
						'model' => $model,
						'sourceUrl' => $this->createUrl('default/parentOptions'),
						'name' => 'short_name',
						'options' => array(
							'minLength'=>2,				
						),
						'htmlOptions' => array(
							'size'=>35,
							'maxlength'=>255,
						),
					)
				);
		?>
		<?php echo $form->error($model,'short_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
			<?php echo $form->labelEx($model,'parent_company_id'); ?>
			<?php $this->widget('parallel\yii\zii\widgets\jui\JuiAutoCompleteFk',
		          array(
		            //name of the html field that will be generated
					'model' => $model,
		            'attribute' => 'parent_company_id',
					'sourceUrl' => $this->createUrl("default/parentOptions"),
		          	'relName' => 'parentCompany',
		          	'displayAttr' => 'short_name',
		          	'methodChain' => '.data( "autocomplete" )._renderItem = function( ul, item ) {
	        			return $( "<li></li>" )
	            		.data( "item.autocomplete", item )
	            		.append( "<a>" + item.label +  "<br><font size=0.8em> " + item.detail + "</font></a>" )
	            		.appendTo( ul );
	    			};'
		          ));
	    	?>
			<?php echo $form->error($model,'parent_company_id'); ?>
	</div>
 	
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