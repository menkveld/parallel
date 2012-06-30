<?php
$this->breadcrumbs=array(
	'Persons'=>array('index'),
	'List',
);

$this->menu=array(
	array('label'=>'New Person', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('person-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'person-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		//'id',
		'surname',
		'preferred_name',
		'full_name',
		'date_of_birth',
		'gender',
		/*
		'national_id_number',
		'passport_number',
		'passport_country_id',
		*/
		array(
			'class'=>'CButtonColumn',
			'htmlOptions' => array('class' => 'button-column',
									'style' => 'width: 100px',
									)
		),
	),
)); ?>
