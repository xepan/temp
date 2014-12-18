<?php

class Model_EpanTemplates extends Model_Table {
	var $table= "epan_templates";
	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->addField('body_attributes')->type('text');
		$f=$this->addField('name')->group('a~12~<i class="fa -fa-clipboard"></i> Epan Template')->mandatory(true);
		$f->icon = 'fa fa-clipboard~red';
		$this->addField('content')->type('text')->defaultValue('<div component_namespace="baseElements" component_type="TemplateContentRegion" class="epan-sortable-component epan-component  ui-sortable" style="" contenteditable="false">{{Content}}</div>');
		$f=$this->addField('css')->type('text')->group('a~12~bl');
		$f->icon = 'fa fa-eye-slash~blue';
		$this->addField('is_current')->type('boolean')->defaultValue(false);
		
		$this->hasMany('EpanPage','template_id');
		
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		$old_templates = $this->add('Model_EpanTemplates');
		if($this->loaded())
			$old_templates->addCondition('id','<>',$this->id);

		$old_templates->addCondition('name',$this['name']);		
		$old_templates->tryLoadAny();
		if($old_templates->loaded())
			throw $this->exception('Name Already Exists','ValidityCheck')->setField('name');

	}

	function beforeDelete(){
		if($this->ref('EpanPage')->Count()->getOne())
			$this->api->js(true)->univ()->errorMessage('One/More page use this template, Canot Delete')->execute();	
	
	}
}