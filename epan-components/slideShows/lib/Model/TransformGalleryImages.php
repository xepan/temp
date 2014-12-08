<?php

namespace slideShows;

class Model_TransformGalleryImages extends \Model_Table{
	var $table="slideShows_transformgalleryimages";
	function init(){
		parent::init();

		$this->hasOne('slideShows/TransformGallery','gallery_id');

		$f = $this->addField('image')->display(array('form'=>'ElImage'))->group('a~12~<i class="fa fa-picture-o"></i> Transform Gallery Images')->mandatory(true);
		$f->icon = "fa fa-picture-o~red";
		$f = $this->addField('name')->caption('Display Tittle')->group('a~12~bl');
		$f->icon = "fa fa-info~blue";
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}