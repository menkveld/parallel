<?php
$this->breadcrumbs=array(
	'Persons'=>array('index'),
	$model->preferred_name." ".$model->surname,
);

$this->menu=array(
	array('label'=>'List Persons', 'url'=>array('list')),
	array('label'=>'New Person', 'url'=>array('create')),
	array('label'=>'Update Person', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Person', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>
<?php 
	$details = $this->widget('zii.widgets.CDetailView', 
						array(
							'data'=>$model,
							'attributes'=>array(
								'full_name',
								'surname',
								'preferred_name',
								'date_of_birth',
								array(
									'name' => 'Age',
									'value' => CHtml::encode($model->ageText)
								),
								array(
									'name' => 'gender',
									'value' => CHtml::encode($model->genderText)
								),
							),
						), 
						true); 
	
	$auditTrail = $this->widget('parallel.yii.modules.auditTrail.widgets.portlets.ShowAuditTrail', 
						array( 
							'model' => $model, 
							),
						true);
	
	$this->widget('zii.widgets.jui.CJuiTabs', array(
			'tabs'=>array(
					'Detail'=>array('content' => $details),
					'Audit Trail'=>array('content' => $auditTrail)
			),
			// additional javascript options for the tabs plugin
			'options'=>array(
					//'collapsible'=>true,
					'fx' => array('opacity' => 'toggle',
								  'duration' => 'fast')
			),
	));
?>