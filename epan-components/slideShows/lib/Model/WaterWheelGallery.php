<?php
namespace slideShows;
class Model_WaterWheelGallery extends \Model_Table {
	var $table= "slideShows_waterwheelgallery";
	function init(){
		parent::init();
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('name')->Caption('Gallery Name')->mandatory(true)->group('a~6');
		$f->icon = "fa fa-picture-o~red";
		$f = $this->addField('animation')->enum(array('linear','swing'))->group('a~3');
		$f->icon = "fa fa-magic~blue";
		$f = $this->addField('orientation')->enum(array('horizontal','vertical'))->group('a~3');
		$f->icon = "fa fa-road~blue";
		$f = $this->addField('show_item')->defaultValue(5)->group('b~2');
		$f->icon = "fa fa-youtube-play~blue";
		// $this->addField('speed')->defaultValue(3000);
		$f = $this->addField('separation')->defaultValue(200)->group('b~2');
		$f->icon = "fa fa-filter~blue";
		$f = $this->addField('size_multiplier')->defaultValue(0.7)->group('b~2');
		$f->icon = "fa fa-asterisk~blue";
		$f = $this->addField('opacity')->defaultValue(0.8)->group('b~2');
		$f->icon = "fa fa-circle";
		$f = $this->addField('autoPlay')->defaultValue(3000)->Caption('Auto Play Speed')->group('b~2');
		$f->icon = "";
		$f = $this->addField('keyboard_Nav')->type('boolean')->group('b~2');
		$f->icon = "fa fa-exclamation~blue";
		$f = $this->addField('is_publish')->type('boolean')->defaultValue(true)->group('c~2');
		$f->icon = "fa fa-exclamation~blue";

		$this->hasMany('slideShows/Model_WaterWheelImages','gallery_id');
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}