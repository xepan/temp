<?php

namespace xShop;
class Model_Manufacturer extends \Model_Table {
	var $table= "xshop_manufacturer";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('name')->caption('Company Name')->mandatory(true)->group('a~5~<i class="fa fa-info"></i> Basic Info');
		$f->icon = "fa fa-circle~red";
		$f = $this->addField('logo_url')->display(array('form'=>'ElImage'))->group('a~5');
		$f->icon = "glyphicon glyphicon-picture~blue";
		$f = $this->addField('is_active')->type('boolean')->defaultValue('true')->group('a~2');
		$f->icon ="fa fa-exclamation~blue";

		$f = $this->addField('phone_no')->type('number')->group('c~3~<i class="fa fa-link"></i> Digital Contact');
		$f->icon="fa fa-phone~blue";
		$f = $this->addField('mobile_no')->type('number')->group('c~3');
		$f->icon="fa fa-mobile~blue";
		$f = $this->addField('email_id')->group('c~3');
		$f->icon="fa fa-envelope~blue";
		$f = $this->addField('website_url')->group('c~3');
		$f->icon="fa fa-globe~blue";
		
		$this->addField('office_address')->type('text')->mandatory(true)->group('b~12~<i class="fa fa-credit-card"></i> Address');
		$f = $this->addField('city')->group('b~3~Address');
		$this->addField('state')->group('b~3~Address');
		$this->addField('country')->group('b~3~Address');
		$this->addField('zip_code')->caption('Zip/postal code')->group('b~2');
		
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'));
		$f->icon = "fa fa-pencil~blue";
		$this->hasMany('xShop/Product','manufacturer_id');

		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete($m){
		if($m->ref('xShop/Product')->count()->getOne())
			$this->api->js(true)->univ()->errorMessage('First Delete its associated Item')->execute();		
	}
	
}