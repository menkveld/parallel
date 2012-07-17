<div class="well" style="padding: 8px 0;">
	<ul class="nav nav-list" data-bind="foreach: options">
		<li data-bind="css: { active: $data == $root.currentOptionId() }">
			<a data-bind="text: $data, click: $root.selectOption"></a>
        </li>
	</ul>
</div>
