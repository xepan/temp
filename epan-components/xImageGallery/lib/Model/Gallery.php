<?php

namespace xImageGallery;


class Model_Gallery extends \Model_Table {
	public $table ='xImageGallery_gallery';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$f = $this->addField('name')->mandatory(true)->hint('Gallery Name')->group('a~12~<i class="fa fa-cog"></i> Google Gallery');
		// $this->addField('matter')->type('text')->display(array('form'=>'RichText'))->defaultValue('<p></p>');
		$this->hasMany('xImageGallery/Images','gallery_id');
			
		// $this->add('dynamic_model/Controller_AutoCreator');
	}
}