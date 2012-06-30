	<tr id="contactDetailItem_tr_<?php echo $contactDetailItem->id?>">
		<td>
			<?php echo \CHtml::activeHiddenField($contactDetailItem, '['.$index.']id');?>
			<?php echo \CHtml::activeHiddenField($contactDetailItem->contactDetail, '['.$index.']type_id');?>
			<?php echo \CHtml::activeTextField($contactDetailItem,'['.$index.']label',array('size'=>20,'maxlength'=>255)); ?>
			<?php echo \CHtml::error($contactDetailItem,'label'); ?>
		</td>
 		<td>
			<?php echo \CHtml::activeTextField($contactDetailItem->contactDetail,'['.$index.']value',array('size'=>25,'maxlength'=>255)); ?>
			<?php echo \CHtml::error($contactDetailItem->contactDetail,'value'); ?>
		</td>
		<td>
			<?php echo \CHtml::activeTextArea($contactDetailItem,'['.$index.']notes',array('maxlength'=>255, 'style' => 'height: 16px; width: 390px;')); ?>
			<?php echo \CHtml::error($contactDetailItem,'notes'); 
			
					// Only display delete icon if this is an existing contact detail item
					if($contactDetailItem->id) {
						$deleteImageURL = \Yii::app()->theme->baseUrl.'/css/images/spacer.gif';	// Get a transparent spacer
						echo \CHtml::image($deleteImageURL, "Delete contact detail item",
								array('class' => 'ui-icon ui-icon-trash',
										//'name'=>'remove_'.$this->attribute,
										'style'=>'margin: 3px 0px 0px 0px; float: right;',
										//'onclick'=>"$('#".$this->_fieldName."').val('');$('#".$this->_saveName."').val('');$('#".$this->_lookupName."').val('');",
										'onclick' => "deleteItem({$contactDetailItem->id});",
								)
						);
					}
			?>
		</td>
	</tr>