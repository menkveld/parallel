<form class="form-horizontal" data-bind="with: profileData">
	<fieldset>
		<legend><?php echo Yii::t('user','Profile')?></legend>
		<div data-bind="css: { error: $parent.hasError('email') }" class="control-group">
			<label class="control-label" for="email"><?php echo Yii::t('site','Email')?></label>
			<div class="controls">
				<input data-bind="value: email" type="text"/>
				<span class="help-inline" data-bind="visible: $parent.hasError('email'), text: $parent.hasError('email')"></span>
			</div>
		</div>
		<div data-bind="css: { error: $parent.hasError('name') }" class="control-group">
			<label class="control-label" for="name"><?php echo Yii::t('user','Name')?></label>
			<div class="controls">
				<input data-bind="value: name" type="text"/>
				<span class="help-inline" data-bind="visible: $parent.hasError('name'), text: $parent.hasError('name')"></span>
			</div>
		</div>
		<div data-bind="css: { error: $parent.hasError('surname') }" class="control-group">
			<label class="control-label" for="surname"><?php echo Yii::t('user','Surname')?></label>
			<div class="controls">
				<input data-bind="value: surname" type="text"/>
				<span class="help-inline" data-bind="visible: $parent.hasError('surname'), text: $parent.hasError('surname')"></span>
			</div>
		</div>
		<div class="form-actions">
	    	<button data-bind="click: $parent.saveProfile" class="btn btn-primary span1 offset1"><?php echo Yii::t('site','Save')?></button>
	    	<span class="help-inline span1 offset3" id="saving_spinner"></span>
	    </div>	
	</fieldset>
</form>
