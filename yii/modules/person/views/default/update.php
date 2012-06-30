<?php
$this->breadcrumbs=array(
	'Persons'=>array('index'),
	$model->preferred_name.' '.$model->surname=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Persons', 'url'=>array('list')),
	array('label'=>'Create Person', 'url'=>array('create')),
);
?>
<div class="ui-widget">
	<div class="ui-widget-header ui-corner-top parallel-ui-widget-header">
		<?php echo $model->preferred_name.' '.$model->surname ?>
	</div>
	<div class="ui-widget-content ui-corner-bottom parallel-ui-widget-content">
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>