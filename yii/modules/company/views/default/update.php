<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Companies', 'icon'=>'list', 'url'=>array('list')),
	array('label'=>'New Company', 'icon'=>'pencil', 'url'=>array('create')),
);
?>

<?php echo $this->renderPartial('_form_bs', array('model'=>$model)); ?>