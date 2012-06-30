<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	'New',
);

$this->menu=array(
	array('label'=>'List Companies', 'url'=>array('list')),
);
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>