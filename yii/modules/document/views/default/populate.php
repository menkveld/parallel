<legend><?php echo $document->name; ?></legend>
<?php
	foreach($structure as $level1) {
		$tab = array(
					'label'=>$level1->label,
					'content'=>$this->renderPartial('_level2', array('path' => array($document->id), 'parent' => $level1), true),
				);
		$tabs[] = $tab;
	}
	// Set the first tab active
	$tabs[0]['active'] = true;
	
	// Create content
	$this->widget('bootstrap.widgets.TbTabs', array(
		    'type'=>'tabs',
		    'placement'=>'left', // 'above', 'right', 'below' or 'left'
		    'tabs'=> $tabs,
		)
	); 
?>
<script>
	// Save each field on change
	$("textarea").change( 
		function () { 
			console.log("Saving field: "+this.id); 
			// Save field via AJAX call
		});
</script>