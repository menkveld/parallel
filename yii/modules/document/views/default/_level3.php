<?php
	// Fields
	foreach($parent->documentStructureFields as $idx => $field) {
		$path[] = $parent->name;
		$fieldPath = $this->createFieldPath($path);		
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

foreach($parent->documentStructures as $idx => $item) {
	$this->renderPartial('_levelx', array('path' => $path, 'parent' => $item));
}
?>