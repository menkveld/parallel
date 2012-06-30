<?php echo \CHtml::activeHiddenField($addressItem, '['.$index.']id');?>
<?php echo \CHtml::activeHiddenField($addressItem->address, '['.$index.']type_id');?>
	
<div class="address_block">
	<div class="row">
		<?php echo \CHtml::activeTextField($addressItem,'['.$index.']label',array('size'=>27,'maxlength'=>255)); ?>
		<?php echo \CHtml::error($addressItem,'label'); ?>
	</div>
	<div class="row">
		<?php echo \CHtml::activeLabelEx($addressItem->address,'line_1'); ?>
		<?php echo \CHtml::activeTextField($addressItem->address,'['.$index.']line_1',array('size'=>27,'maxlength'=>255)); ?>
		<?php echo \CHtml::error($addressItem->address,'line_1'); ?>
	</div>
	<div class="row inline_field">
		<?php echo \CHtml::activeLabelEx($addressItem->address,'line_2'); ?>
		<?php echo \CHtml::activeTextField($addressItem->address,'['.$index.']line_2',array('size'=>3,'maxlength'=>7)); ?>
		<?php echo \CHtml::error($addressItem->address,'line_2'); ?>
	</div>	
	<div class="row">
		<?php echo \CHtml::activeLabelEx($addressItem->address,'line_3'); ?>
		<?php echo \CHtml::activeTextField($addressItem->address,'['.$index.']line_3',array('size'=>19,'maxlength'=>255)); ?>
		<?php echo \CHtml::error($addressItem->address,'line_3'); ?>
	</div>	
	<div class="row inline_field">
		<?php echo \CHtml::activeLabelEx($addressItem->address,'suburb_label'); ?>
		<?php //echo \CHtml::activeTextField($addressItem->address,'['.$index.']suburb_label',array('size'=>20,'maxlength'=>255)); ?>
		<?php
			$this->widget('parallel\yii\zii\widgets\jui\JuiAutoComplete', array(
					'model' => $addressItem->address,
					'attribute' => 'suburb_label',

					// The default behavior of the Auto Complete widget is to render the 
					// ID fiels with the index in [] in multi field array cases. This is a problem for jQuery
					// and it can not hook the auto complete functionality to a field with such an ID.
					// set multipleItemGroup = true will fix this issue. See JuiAutoComplete class for more.
					'multipleItemGroup' => true,	// Indicates that this widget is part of a form with multiple items. E.g. addresses or contacts
					'index' => $index,				// Current widget index

					'sourceUrl' => \CHtml::normalizeUrl(array("default/suburbOptions")),	// URL of the ajax call to return the suburb options

					// additional javascript options for the jQuery autocomplete plugin
					'options'=>array(
								'minLength'=>'3',
								// Javascript function that will be called when ajax returns.
								'select' => 'js:function(event, ui){ 
													console.log(\'Selected: \' + ui.item.label + \', \' + ui.item.province_state + \', \' + ui.item.postal_code);
													$(\'[multiId=suburb_postal_code_'.$index.']\').val(ui.item.postal_code);
													$(\'[multiId=suburb_province_state_'.$index.']\').val(ui.item.province_state);
												}',
							),

					// Appended to the end of the jQuery call
					'methodChain' => '.data("autocomplete")._renderItem = function( ul, item ) {
						return $("<li></li>")
						.data("item.autocomplete", item)
						.append("<a>" + item.label +  "<font size=0.8em> " + item.postal_code + ", " + item.province_state + "</font></a>")
						.appendTo(ul);
					};',					
			));
    	?>
		<?php echo \CHtml::error($addressItem->address,'suburb_label'); ?>
	</div>	
	<div class="row">
		<?php echo \CHtml::activeLabelEx($addressItem->address,'suburb_postal_code'); ?>
		<?php echo \CHtml::activeTextField($addressItem->address,'['.$index.']suburb_postal_code',array('size'=>3,'maxlength'=>7, 'multiID' => 'suburb_postal_code_'.$index)); ?>
		<?php echo \CHtml::error($addressItem->address,'suburb_postal_code'); ?>
	</div>	
	<div class="row">
		<?php echo \CHtml::activeLabelEx($addressItem->address->suburb,'province_state_label'); ?>
		<?php //echo \CHtml::dropDownList('AddContactDetailSelect', '', CHtml::listData($contactDetailItems[0]->contactDetail->typeOptions, 'id', 'label'), array('prompt'=>'Add'));?>	
		<?php echo \CHtml::activeTextField($addressItem->address->suburb,'['.$index.']province_state_label',array('size'=>15,'maxlength'=>255, 'multiID' => 'suburb_province_state_'.$index)); ?>
		<?php echo \CHtml::error($addressItem->address->suburb,'province_state_label'); ?>
	</div>	
	<div class="row">
		<?php
			// Only display delete icon if this is an existing contact detail item
			$IconImageURL = \Yii::app()->theme->baseUrl.'/css/images/spacer.gif';	// Get a transparent spacer
			if($addressItem->id) {
				echo \CHtml::image($IconImageURL, "Delete contact detail item",
						array('class' => 'ui-icon ui-icon-trash',
								//'name'=>'remove_'.$this->attribute,
								'style'=>'margin: 0px 0px 0px 0px; float: right;',
								//'onclick' => "deleteItem({$contactDetailItem->id});",
						)
				);
			}
	
			echo \CHtml::activeLabelEx($addressItem->address->suburb->provinceState,'country');
			echo $addressItem->address->suburb->provinceState->country->label; 
			echo \CHtml::image($IconImageURL, "Delete contact detail item",
					array('class' => 'ui-icon ui-icon-pencil',
							//'name'=>'remove_'.$this->attribute,
							'style'=>'margin: 0px 0px 0px 0px;display: inline-block;',
							//'onclick' => "deleteItem({$contactDetailItem->id});",
					)
			);
				
		?>
	</div>	
</div>