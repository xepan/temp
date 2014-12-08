<?php

namespace xShop;
class Model_Supplier extends \Model_Table {
	var $table= "xShop_supplier";
	function init(){
		parent::init();

		//TODO for Mutiple Epan website
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		
		$f = $this->addField('name')->mandatory(true)->group('a~10~<i class="fa fa-info"></i> Basic Info');
		$f->icon = "fa fa-user~red";
		$f = $this->addField('is_active')->type('boolean')->defaultValue(true)->group('a~2');
		$f->icon = "fa fa-exclamation~blue";

		$f = $this->addField('email_id')->group('b~4~<i class="fa fa-link"></i> Digital Contact');
		$f->icon = "fa fa-envelope~blue";
		$f = $this->addField('phone_no')->type('number')->group('b~4');
		$f->icon = "fa fa-phone~blue";
		$f = $this->addField('mobile_no')->type('number')->group('b~4');
		$f->icon = "fa fa-mobile~blue";

		$this->addField('office_address')->type('text')->group('c~6~<i class="fa fa-credit-card"></i> Address');
		$this->addField('address')->type('text')->group('c~6');
		$this->addField('city')->group('c~3~dl');
		$this->addField('state')->group('c~3~dl');
		$this->addField('country')->group('c~3~dl');
		$this->addField('zip_code')->caption('Zip/postal code')->group('c~2~dl');
		$f = $this->addField('description')->type('text')->display(array('form'=>'RichText'));
		$f->icon = "fa fa-pencil~blue";
		$this->hasMany('xShop/Product','supplier_id');
		$this->addHook('beforeDelete',$this);
		// $this->add('dynamic_model/Controller_AutoCreator');

	}

	function beforeDelete($m){
		if($m->ref('xShop/Product')->count()->getOne())
			$this->api->js(true)->univ()->errorMessage('First Delete,Associated Items')->execute();		
	}

}
