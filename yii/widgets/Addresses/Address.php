<?php
namespace parallel\yii\widgets\Addresses;

class Address extends \CWidget {
	
	public $model;
	
	public $index;
	
	/**
	 * Indicates the country format to be used for the address layout. 
	 * This is specified with the ISO contry code. The format.php view
	 * from the corresponding country subdirectory e.g. AU will be used.
	 */
	public $country = 'AU';	

	/**
	 * Indicates the address type to be displayed. E.g. postal or physical 
	 */
	public $type;
	
	public function init(){
	
	}
	
	public function run() {
		$deleteImageURL = \Yii::app()->theme->baseUrl.'/css/images/spacer.gif';	// Get a transparent spacer
		$this->render('format/'.$this->country.'/format',
				array('addressItem' => $this->model,
						'index' => $this->index,
						'type' => $this->type,
						'deleteImageURL' => $deleteImageURL
					)
		);
	}
}