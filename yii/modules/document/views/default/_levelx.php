<p><h5>
<?php 
	if(isset($ancestors)) {
		$ancestors .= " - ";
		echo $ancestors;
	} else {
		$ancestors = "";
	}
	echo $parent->label;
	
?>
</h5></p>

<?php
	$path[] = $parent->name;
	$fieldPath = $this->createFieldPath($path);

	// Fields
	// TDB: Make provision for other field types e.g. images
	foreach($parent->documentStructureFields as $idx => $field) {
?>
		<div class="row-fluid">
		  	<div class="span7">
			    <div class="controls">
					<textarea class="span12" id="<?php echo $fieldPath;?>" rows="<?php echo $field->field_size;?>"></textarea>
			    </div>
		  	</div>
		  	<div class="span5">
		  		<p><?php echo $field->help_html;?></p>
		  	</div>
	   	</div>
<?php		
	}
		
	// At this level, if there is not field defined, add a text field by default.
	if(empty($parent->documentStructureFields)) {
?>
		<div class="row-fluid">
		  	<div class="span7">
			    <div class="controls">
					<textarea class="span12" id="<?php echo $fieldPath;?>" rows="5"></textarea>
			    </div>
		  	</div>
	   	</div>
<?php	
	}

	// If there are more levels, call this view recursively
	foreach($parent->documentStructures as $idx => $item) {
		$this->renderPartial('_levelx', array('path' => $path, 
											  'parent' => $item,
											  'ancestors' => $ancestors.$parent->label
		));
	}
?>