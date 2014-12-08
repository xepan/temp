<?php

namespace slideShows;
class Model_ThumbnailSliderGallery extends \Model_Table {
	var $table= "slideShows_thumbnailslidergallery";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$f = $this->addField('name')->Caption('Gallery Name')->mandatory(true)->group('a~6~<i class="fa fa-picture-o"></i> Thumbnail Slider Gallery Options');
		$f->icon = "fa fa-film~red";	
		$f = $this->addField('direction')->enum(array('vertical','horizontal'))->defaultValue('horizontal')->group('a~2');
		$f->icon = "fa fa-road~blue";	
		$f = $this->addField('scroll_intervarl')->defaultValue(2400)->group('a~2');
		$f->icon = "fa fa-spinner~blue";	
		$f = $this->addField('scroll_duration')->defaultValue(1200)->group('a~2');
		$f->icon = "fa fa-spinner~blue";	
		$f = $this->addField('on_hover')->type('boolean')->defaultValue(true)->Caption('Mouse Hover Stop Slide')->group('b~3');
		$f->icon = "fa fa-exclamation~blue";	
		$f = $this->addField('autoAdvance')->type('boolean')->defaultValue(true)->Caption('Auto Slider')->group('b~3');
		$f->icon = "fa fa-exclamation~blue";	
		$f = $this->addField('scroll_by_each_thumb')->type('boolean')->defaultValue(true)->group('b~3');
		$f->icon = "fa fa-exclamation~blue";	
		$f = $this->addField('is_publish')->type('boolean')->defaultValue(true)->group('b~3');
		$f->icon = "fa fa-exclamation~blue";	

		$this->hasMany('slideShows/Model_ThumbnailSliderImages','gallery_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}