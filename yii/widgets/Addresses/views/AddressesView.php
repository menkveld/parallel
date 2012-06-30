<style>
	.inline_field {
		float: left;
	}

	.address_block {
		display: inline-block;
		margin: 0px 38px 20px 0px;
		border: 1px solid #87B6D9;
		padding: 3px;
	}

	.ui-autocomplete {
		max-height: 200px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		/* add padding to account for vertical scrollbar */
	}

	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 100px;
	}
</style>

<?php
	// Loop through all the addresses and display each 
	foreach($addressItems as $idx => $addressItem) {
		$this->widget('parallel\yii\widgets\Addresses\Address',
				array(
						'model' => $addressItem,
						'index' => $idx,
				)
		);
	}

?>
<div class="address_block">
<?php 
	$IconImageURL = \Yii::app()->theme->baseUrl.'/css/images/spacer.gif';	// Get a transparent spacer
	echo \CHtml::image($IconImageURL, "Add new address",
		array('class' => 'ui-icon ui-icon-circle-plus',
				'style'=>'margin: 3px 0px 0px 0px; float: right;',
				)
			);
?>
</div>
