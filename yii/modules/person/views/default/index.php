<?php
$this->breadcrumbs=array(
	'Persons',
);

$this->menu=array(
	array('label'=>'Create Person', 'url'=>array('create')),
	array('label'=>'List Persons', 'url'=>array('list')),
);
?>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
