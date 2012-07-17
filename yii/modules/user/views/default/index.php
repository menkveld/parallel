<?php
	Yii::app()->clientScript->registerScriptFile(\Yii::app()->assetManager->publish(\Yii::getPathOfAlias('vendors').'/knockout').'/knockout.js');
	Yii::app()->clientScript->registerScriptFile(\Yii::app()->assetManager->publish(\Yii::getPathOfAlias('vendors').'/spin.js').'/spin.min.js');
	Yii::app()->clientScript->registerScriptFile(\Yii::app()->assetManager->publish(\Yii::getPathOfAlias('vendors').'/spin.js').'/jquery.spin.js');
	Yii::app()->clientScript->registerScriptFile($this->module->getAssetsUrl(true).'/js/user.js', CClientScript::POS_END);	// user ViewModel
	
	$this->breadcrumbs=array(
		$this->module->id,
	);
?>
<script>
	var restUrl = '<?php echo \CHtml::normalizeUrl(array('/rest/user/'.$model->id));?>';
</script>

<div class="row-fluid">
	<div id="sidebar" class="span2">
		<?php $this->renderPartial('_sidebar');?>
	</div><!-- sidebar -->
	<div id="content" class="span10">
		<div id="spinner" class="span2" style="height: 100px;"></div>
		<?php $this->renderPartial('_profile');?>
		<?php $this->renderPartial('_settings');?>
	</div><!-- content -->
</div><!-- row-fluid -->