<?php
$this->breadcrumbs=array(
	'Companies'=>array('index'),
	$model->short_name,
);

$this->menu=array(
	array('label'=>'List Companies', 'url'=>array('list')),
	array('label'=>'New Company', 'url'=>array('create')),
	array('label'=>'Update Company', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Company', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<?php
	$details = $this->widget('zii.widgets.CDetailView', 
					array(
						'data'=>$model,
						'attributes'=>array(
							'short_name',
							'name',
							'parent_company_id',
						)),
					true); 

	$auditTrail = $this->widget('parallel.yii.modules.auditTrail.widgets.portlets.ShowAuditTrail',
					array( 
						'model' => $model, 
						),
					true
				);
	
	$this->widget('zii.widgets.jui.CJuiTabs', array(
			'tabs'=>array(
					'Detail'=>array('content' => $details),
					'Audit Trail'=>array('content' => $auditTrail)
			),
			// additional javascript options for the tabs plugin
			'options'=>array(
					//'collapsible'=>true,
			),
	));
	
?>
