<?php

namespace slideShows;

class Model_TransformGallery extends \Model_Table{
	var $table="slideShows_transformgallery";
	function init(){
		parent::init();

		$this->hasOne('Model_Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('name')->mandatory(true)->Caption('Gallery Name')->group('a~6~<i class="fa fa-cog"></i> Transform Gallery');
		$f->icon = "fa fa-picture-o~red";
		$f = $this->addField('interval')->Caption('Time Interval')->defaultValue('2000')->hint('Set Time Interval In Mili-Second')->group('a~4');
		$f->icon = "fa fa-spinner~blue";
		$f = $this->addField('autoplay')->Caption('Auto Play')->type('boolean')->defaultValue(true)->group('a~2');
		$f->icon = "fa fa-exclamation~blue";
		
		$this->hasMany('slideShows/TransformGalleryImages','gallery_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}