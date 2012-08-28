<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'company-form',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('class' => "well"),
	'type' => 'horizontal',
)); 
?>
	<legend>Company Details</legend>
	<div class="row-fluid">
		<div class="span6">
			<?php $this->widget('parallel\yii\zii\widgets\jui\JuiBSAutoComplete',
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
			
			<?php echo $form->textFieldRow($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		</div>
		<div class="span6">
			<?php $this->widget('parallel\yii\zii\widgets\jui\JuiBSAutoCompleteFk',
		          array(
		            //name of the html field that will be generated
					'model' => $model,
		            'attribute' => 'parent_company_id',
					'sourceUrl' => $this->createUrl("default/parentOptions"),
		          	'relName' => 'parentCompany',
		          	'displayAttr' => 'short_name',
		          	'deleteTooltip' => 'Unlink from Parent Company',
		          	'methodChain' => '.data( "autocomplete" )._renderItem = function( ul, item ) {
	        			return $( "<li></li>" )
	            		.data( "item.autocomplete", item )
	            		.append( "<a>" + item.label +  "<br><font size=0.8em> " + item.detail + "</font></a>" )
	            		.appendTo( ul );
	    			};'
		          ));
			?>
		</div>
	</div>
	
	<legend>Contact Details</legend>	
	<div class="row-fluid">
		<?php 
			$this->widget('parallel\yii\widgets\ContactDetails\ContactDetails',
				array(
					'model' => $model,
				)
			);
		?>
	</div>

	<div class="form-actions">
	    <?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit', 'type'=>'primary', 'icon'=>'ok white', 'label'=>'Submit')); ?>
	</div>	

<?php $this->endWidget(); ?>