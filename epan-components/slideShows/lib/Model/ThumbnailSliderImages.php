<?php
namespace slideShows;

class Model_ThumbnailSliderImages extends \Model_Table {
	var $table= "slideShows_thumbnailsliderimages";
	function init(){
		parent::init();

		$this->hasOne('slideShows/Model_ThumbnailSliderGallery','gallery_id');

		$f = $this->addField('image')->display(array('form'=>'ElImage'))->mandatory(true)->group('a~8~<i class="fa fa-picture-o"></i> Thumbanil Slider Images');
		$f->icon = "glyphicon glyphicon-picture~red";
		$f = $this->addField('description')->type('text')->group('a~5~bl');
		$f->icon = "fa fa-pencil~blue";
		$f = $this->addField('order_no')->type('int')->group('a~4');
		$f->icon = "fa fa-sort-amount-asc~blue";
		$f = $this->addField('tooltip')->type('text')->group('a~5~bl');
		$f->icon = "fa fa-pencil~blue";
		
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}