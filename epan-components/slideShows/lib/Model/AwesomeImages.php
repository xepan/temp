<?php
namespace slideShows;
class Model_AwesomeImages extends \Model_Table {
	var $table= "slideshows_awesomeimages";
	function init(){
		parent::init();

		$this->hasOne('slideShows/AwesomeGallery','gallery_id');
		$f = $this->addField('image')->display(array('form'=>'ElImage'))->group('a~12~<i class="glyphicon glyphicon-picture"></i> Awesome Gallery Images')->mandatory(true);
		$f->icon = "glyphicon glyphicon-picture~red";
		$f = $this->addField('tag')->group('a~11~dl');
		$f->icon = "fa fa-tag~blue";
		$f = $this->addField('order_no')->type('int')->group('a~3~dl');
		$f->icon = "fa fa-sort-amount-asc~blue";
		$f = $this->addField('effects')->enum(array("sliceDownRight","sliceDownLeft","sliceUpRight","sliceUpLeft","sliceUpDown","sliceUpDownLeft","fold","fade","boxRandom","boxRain","boxRainReverse","boxRainGrow","boxRainGrowReverse"))->defaultValue('Please Select')->group('a~6~dl');
		$f->icon = "fa fa-magic~blue";
		$f = $this->addField('is_publish')->type('boolean')->defaultValue(true)->group('a~2~dl');
		$f->icon = "fa fa-exclamation~blue";
		$this->addHook('beforeSave',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave($m){

		$old_img_model=$this->add('slideShows/Model_AwesomeImages');
		$old_img_model->setOrder('order_no','desc');
		$old_img_model->tryLoadAny();
		if(!$m->loaded())
			$m['order_no']=$old_img_model['order_no']+1;
		// throw new \Exception($old_img_model['order_no']+1);

		// 	// if($old_gallery_model['folder_path'] != urldecode($m['folder_path'])){
		// 		$image_model=$this->add('slideShows/Model_AwesomeImages');
		// 		$image_model->addCondition('gallery_id',$old_gallery_model['id']);
		// 		$image_model->tryLoadAny();
		// 		$image_model->deleteAll();		
	}

	function createNew($gallery_id,$image_url,$order_id){
		
		$this['gallery_id'] = $gallery_id;
		$this['image'] = $image_url;
		$this['effects'] = "";
		$this['order_no'] = $order_id;
		$this['is_publish'] = true;
		$this->saveAndUnload();
	}


}