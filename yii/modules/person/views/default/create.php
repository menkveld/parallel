<?php
$this->breadcrumbs=array(
	'Persons'=>array('index'),
	'New',
);

$this->menu=array(
	array('label'=>'List Persons', 'url'=>array('index')),
);
?>
<div class="ui-widget">
	<div class="ui-widget-header ui-corner-top parallel-ui-widget-header">
		New Person
	</div>
	<div class="ui-widget-content ui-corner-bottom parallel-ui-widget-content">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
