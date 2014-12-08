<?php

namespace xImageGallery;

class Model_Images extends \Model_Table {
	public $table ='xImageGallery_images';

	function init(){
		parent::init();
		
		$this->hasOne('xImageGallery/Gallery','gallery_id');
			
		$f = $this->addField('image_url')->display(array('form'=>'ElImage'))->mandatory(true)->group('a~10~<i class="fa fa-picture-o"></i> Media Management');
		$f->icon = "fa fa-picture-o~red";
		$f = $this->addField('title')->group('a~12~bl');
		$f->icon = "fa fa-info~blue";
		$f = $this->addField('name')->type('text')->display(array('form'=>'RichText'))->defaultValue('<p></p>')->caption('Description')->group('a~10~bl');
		$f->icon = "fa fa-pencil~blue";
		$f = $this->addField('is_publish')->type('boolean')->defaultValue(true)->group('a~2');
		$f->icon = "fa fa-exclamation~blue";
						
		// $this->add('dynamic_model/Controller_AutoCreator');

	}
}	