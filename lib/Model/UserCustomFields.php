<?php

class Model_UserCustomFields extends \Model_Table{
	public $table='user_custom_fields';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);		
		
		$f=$this->addField('name')->caption('Field Name')->group('cf~4~<i class="fa fa-puzzle-piece"></i> Field Information')->mandatory(true);
		$f->icon="fa fa-puzzle-piece~red";
		$f=$type=$this->addField('type')->setValueList(array(
													'Number'=>'INTEGER',
													'line'=>'LINE',
													'text'=>'TEXT',
													'password'=>'PASSWORD',
													'radio'=>'radio', 
													'checkbox'=>'checkbox',
													'dropdown'=>'DROPDOWN',
													'DatePicker'=>'DATE',
													'Upload'=>'UPLOAD',
													'captcha'=>'Captcha'))->group('cf~4');
		$f->icon= 'fa fa-question~red';
		$f=$this->addField('mandatory')->type('boolean')->Caption('Requird Field')->group('cf~2');
		$f->icon = 'fa fa-exclamation~red';
		$f=$this->addField('is_editable')->type('boolean')->group('cf~2');
		$f->icon = 'fa fa-exclamation~red';


		$f=$this->addField('is_expandable')->type('boolean')->defaultValue(false)->group('ex~2~<i class="fa fa-cog"></i> DropDown Values');
		$f->icon = 'fa fa-exclamation~blue';
		$f=$this->addField('set_value')->hint('Comma Separated Values i.e. Male,Female,Other')->caption('Values')->group('ex~10');
		$f->icon = 'fa fa-list~blue';
		$f=$this->addField('change')->type('text')->hint('{"Value":["field name"],"Value 2":[""],"Value 3":["Field 1","Field 2","Field 3"]}')->group('ch~12~<i class="fa fa-link"></i> JS Binding');

		$this->hasMany('CustomFieldValue','usercustomefield_id');

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function getCount($epan_id){
		$this->addCondition('epan_id', $epan_id);
		$this->tryLoadAny();
		return $this->count()->getOne()?:0 ;

	}

}

