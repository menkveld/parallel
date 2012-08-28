<legend><?php echo $parent->label; ?></legend>
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
?>
	<div class="accordion" id="accordion_level3">
<?php	
	// Structures
	foreach($parent->documentStructures as $idx => $item) {
?>
	    <div class="accordion-group">
	    	<div class="accordion-heading">
	        	<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion_level2" href="#collapse<?php echo $item->name;?>">
	            	<?php echo $item->label;?>
	            </a>
	        </div>
	        <div id="collapse<?php echo $item->name;?>" class="accordion-body collapse">
	        	<div class="accordion-inner">
	            	<?php $this->renderPartial('_level3', array('path' => $path, 'parent' => $item));?>
	            </div>
	        </div>
	    </div>
<?php
	}
?>
</div>