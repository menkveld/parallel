<?php
namespace parallel\yii\zii\widgets\jui;
\Yii::import('zii.widgets.jui.CJuiAutoComplete');

/**
 * 
 * This class add the methodChain property to the CJuiAutoComplete widget.
 * The menthodChain can be used to chain additional stuff to the widget.
 * 
 * This class also adds the ability to use this field in an array of fields by
 * setting $multipleItemGroup to true. False by default. 
 * 
 * @author Anton Menkveld
 *
 */
class JuiBSAutoComplete extends \CJuiAutoComplete {

	/**
     * @var string the chain of method calls that would be appended at the end of the autocomplete constructor.
     * For example, ".result(function(...){})" would cause the specified js function to execute
     * when the user selects an option.
     */
    public $methodChain;
    
    /**
     * Indicates that this widget is part of a group(array) of multiple of the same items. E.g. addresses or contacts.
     * This will cause the widget to add an addition attribute to the input field called multiId with 
     * a value the same as the attribute field. This is so that the jQuery can correctly select a field with [] in the id and name fields
     * which is nessesary when the field is part of an array of fields.
     */
    public $multipleItemGroup = false;

    /**
     * If this widget is part of a multi item form, you should provide an index for this item. 
     * @var unknown_type
     */
    public $index = 0;
        
    public function init()
    {
    	// Check if this is a multipleItemGroup
		if($this->multipleItemGroup) {
			// Append the multiId attribute to the htmlOption
			$this->htmlOptions['multiId'] = $this->attribute."_".$this->index;
			 
			// Change the given attribute to the array format
			$this->attribute = "[".$this->index."]".$this->attribute;
		}
    	parent::init();
    }
    
    /**
     * Run this widget.
     * This method registers necessary javascript and renders the needed HTML code.
     */
    public function run()
    {
    	echo "<div class='control-group'>";
    	echo \CHtml::activeLabelEx($this->model, $this->attribute, array('class' => 'control-label'));
    	echo "<div class='controls'>";
    	
        list($name,$id)=$this->resolveNameID();
 
        if(isset($this->htmlOptions['id']))
            $id=$this->htmlOptions['id'];
        else
            $this->htmlOptions['id']=$id;
 
        if(isset($this->htmlOptions['name']))
            $name=$this->htmlOptions['name'];
 
        if($this->hasModel())
            echo \CHtml::activeTextField($this->model,$this->attribute,$this->htmlOptions);
        else
            echo \CHtml::textField($name,$this->value,$this->htmlOptions);
 
        if($this->sourceUrl!==null)
            $this->options['source']=\CHtml::normalizeUrl($this->sourceUrl);
        else
            $this->options['source']=$this->source;
 
        $options=\CJavaScript::encode($this->options);
 
        // If this is a multipleItemGroup then select by the specially added multiId rather than id as id wil contain illegal characters i.e. []
        if($this->multipleItemGroup) {
        	$js = "jQuery('[multiId=".$this->htmlOptions['multiId']."]').autocomplete($options){$this->methodChain};";
        } else {
        	$js = "jQuery('#{$id}').autocomplete($options){$this->methodChain};";
        }
 
        $cs = \Yii::app()->getClientScript();
        $cs->registerScript(__CLASS__.'#'.$id, $js);
        
        echo "<p class='help-block'>".\CHtml::error($this->model, $this->attribute)."</p>";
        echo "</div></div>";
    }
}