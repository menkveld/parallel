<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	'New',
);

$this->menu=array(
	array('label'=>'List Companies', 'icon'=>'list', 'url'=>array('list')),
);
?>

<?php echo $this->renderPartial('_form_bs', array('model'=>$model)); ?>