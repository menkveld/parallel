<table id="contactDetailsTable" class="table-condensed">
<thead>
	<tr>
		<th style="text-align: left;"><?php echo \CHtml::activeLabelEx($contactDetailItems[0],'label'); ?></th>
		<th style="text-align: left;"><?php echo \CHtml::activeLabelEx($contactDetailItems[0]->contactDetail,'value'); ?></th>
		<th style="text-align: left;"><?php echo \CHtml::activeLabelEx($contactDetailItems[0],'notes'); ?></th>
	</tr>
</thead>
<tbody>
<?php 
	foreach($contactDetailItems as $idx => $ccd) {
		$this->widget('parallel\yii\widgets\ContactDetails\ContactDetailItem',
			array(
				'model' => $ccd,
				'index' => $idx,
			)
		);		
	}
	$ItemModelName = $ccd->modelName; // Store the detail item model for use in the ajax calls 
?>
</tbody>

</table>
<?php
	$itemsDB = CHtml::listData($contactDetailItems[0]->contactDetail->typeOptions, 'id', 'label');
	$items = array();
	foreach($itemsDB as $itemTypeId => $itemLabel) {
		$items[] = array(
					'label' => $itemLabel,
					'url' => 'javascript:addItem('.$itemTypeId.')',
				);
	}
	
?>
<div class="btn-toolbar ">
    <?php $this->widget('bootstrap.widgets.TbButtonGroup', array(
        'type'=>'primary', // '', 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
        'buttons'=>array(
            array('label'=>'Add ',
            	  'icon'=>'plus-sign white',
            	  'items'=>$items
            )),
    )); ?>
</div>
<!-- 
<div class="row buttons" style="width: 100%; text-align: right;">
<?php 
	$IconImageURL = \Yii::app()->theme->baseUrl.'/css/images/spacer.gif';	// Get a transparent spacer
	echo \CHtml::image($IconImageURL, "Add a new contact detail",
		array('class' => 'ui-icon ui-icon-circle-plus',
				'style'=>'margin: 4px 0px 0px 10px; float: right;',
				)
			);

	echo \CHtml::dropDownList('AddContactDetailSelect', '', CHtml::listData($contactDetailItems[0]->contactDetail->typeOptions, 'id', 'label'), array('prompt'=>'Add'));
		
?>
</div>
 -->
<script>
	$('#AddContactDetailSelect').change(function() {
			if($('#AddContactDetailSelect option:selected').val() != '') {
			$.ajax({
					url: '<?php echo \CHtml::normalizeUrl(array($ajaxAddAction));?>',
					data: {
							index: ($('#contactDetailsTable tr').length - 1),
							type: $('#AddContactDetailSelect option:selected').val(),
						},
					success: function(html) {
							// Append the new row to the table
							$("#contactDetailsTable > tbody:last").append(html);
							// Reselect the Add option
							$('#AddContactDetailSelect').val(0);
							
						},
				});
			}
		});

	function addItem(type_id) {
		$.ajax({
				url: '<?php echo \CHtml::normalizeUrl(array($ajaxAddAction));?>',
				data: {
						index: ($('#contactDetailsTable tr').length - 1),
						type: type_id,
					},
				success: function(html) {
						// Append the new row to the table
						$("#contactDetailsTable > tbody:last").append(html);
						// Reselect the Add option
						$('#AddContactDetailSelect').val(0);
						
					},
			});
	}
	
	// item_idx - Index of the contact detail item in the list.
	// cd_id - database if od the contact detail item
	function deleteItem(cd_id) {
		// Confirm deletion
		if(confirm('Are you sure you want to delete this item?')) {
	
			// Do an ajax call to the server to delete this item
			$.ajax({
					type: 'DELETE',
					url: '<?php echo \CHtml::normalizeUrl(array('/rest/v1/'.$ItemModelName.'/'));?>/' + cd_id,
					data: {},
					success: function() {
						// Contact detail item successfully deleted from the database, update the screen
						$("#contactDetailItem_tr_"+cd_id).remove();
					},
				});
		}
	}
</script>
