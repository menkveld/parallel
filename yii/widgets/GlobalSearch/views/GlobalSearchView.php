<div class="parallel-global-search">
<?php
	$iconPath = Yii::app()->createUrl('images').'/';
	$this->widget('parallel\yii\zii\widgets\jui\JuiAutoComplete',
			array(
				'sourceUrl' => \Yii::app()->createUrl('site/searchAutoComplete'),
				'attribute' => 'search',
				'name' => 'search',
				'options' => array(
					'minLength'=>2,
					'select' => "js:function(event, ui){window.location = ui.item.url;}",				
				),
				'htmlOptions' => array(
					'style'=>'width: 302px;',
					'maxlength'=>255,
				),
	          	'methodChain' => '.data("autocomplete")._renderItem = function( ul, item ) {
        			return $("<li></li>")
            		.data("item.autocomplete", item )
	           		.append("<a><img align=\'left\' src=\''.$iconPath.'"+item.model+"_small.png\'><div style=\'padding-left: 40px;width: 250px;\'><b>" + item.name + "</b><br><font size=0.8em> " + item.description + "</font></div></a>")
	           		.appendTo(ul);
    			};'
				
			)
		);

// Raplace with search image button
/*	
	$this->widget('zii.widgets.jui.CJuiButton',
		array(
			'name' => 'submit',
			'caption' => 'Search',
//			'htmlOptions' => array(
//				'style' => 'height: 22px; width: 60px; vertical-align: middle;padding-top: 0px; text-align: left;'
//			),
		)
	);
*/
?>
</div>