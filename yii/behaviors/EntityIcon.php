<?php
namespace parallel\yii\behaviors;

/**
 * This behavior allows views to get an icon representing the model.
 * Icons should be default be locates in the app/images subdirectory and their name should be the 
 * same as the model name, plus a suffix. E.g. for Company the icons are names Company_small.png, Company_Med.png etc.
 * 
 *  Default format is png
 * 
 * @author Anton Menkveld
 *
 */
class EntityIcon extends \CActiveRecordBehavior {

	/**
	 * Path to the subdirectory where the model images are saved 
	 */
	public $iconPath = 'application.images';

	/**
	 * Specify the icon file format. Default is png 
	 */
	public $iconFormat = 'png';
	
	public function getIconUrl($suffix = 'small') {
		return \Yii::getPathOfAlias($this->iconPath).'/Company'.'_'.$suffix.'.'.$this->iconFormat;
	}
}