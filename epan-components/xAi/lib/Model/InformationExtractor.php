<?php

namespace xAi;

class Model_InformationExtractor extends \Model_Table {
	var $table= "xai_informationextractor";

	function init(){
		parent::init();

		$this->hasOne('xAi/MetaData','meta_data_id');
		
		$this->addField('name')->mandatory(true);
		$this->addField('code')->type('text')->mandatory(true);
		$this->addField('order')->type('int');
		$this->addField('repetation_handler')
		->setValueList(
			array(
				'increase_weight_for_same_key_value'=>'Increase Weight of First information if Value Same',
				'add_if_new_value'=>'Add New Information If Value Changed',
				'always_increase_weight'=>'Increase First Informations Weight Regardless of Value',
				'discard_same_information'=>'Discard if same value is repeated',
				'discard_if_repeated_key'=>'Discard If the information is already there, regardless of value',
				'update_last_value'=>'Update Last value if information is already there',
				'always_add'=>'Always Add Information',
				)
			)->mandatory(true);

		$this->addField('mark_triggering_information')->type('boolean')->defaultValue(false);
		$this->addField('is_active')->type('boolean')->defaultValue(true);
		$this->addHook('beforeSave',$this);
		$this->addHook('beforeDelete',$this);
		$this->addHook('afterInsert',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeSave(){
		
		// TODO : check for existing information_extractor_name

		if($this->dirty['name'] and $this->loaded()){
			$old_name = $this->add('xAi/Model_InformationExtractor');
			$old_name->load($this->id);

			$meta_information = $this->add('xAi/Model_MetaInformation');
			$meta_information->addCondition('name',$old_name['name']);
			$meta_information->tryLoadAny();

			$meta_information['name'] = $this['name'];
			$meta_information->saveAndUnload();

		}

		if($this->dirty['mark_triggering_information'] and $this->loaded()){

			$meta_information = $this->add('xAi/Model_MetaInformation');
			$meta_information->addCondition('name',$this['name']);
			$meta_information->tryLoadAny();

			$meta_information['is_triggering'] = $this['mark_triggering_information'];
			$meta_information->saveAndUnload();

		}
	}


	function afterInsert($model,$new_id){
		$info_extractor = $this->add('xAi/Model_InformationExtractor');
		$info_extractor->load($new_id);
		
		$meta_information = $this->add('xAi/Model_MetaInformation');
		$meta_information['name'] = $info_extractor['name'];
		$meta_information['is_triggering'] = $info_extractor['mark_triggering_information'];
		$meta_information->save(); 
	}

	function beforeDelete(){
		$meta_information = $this->add('xAi/Model_MetaInformation');
		$meta_information->addCondition('name',$this['name']);
		$meta_information->tryLoadAny();

		if($meta_information->loaded())
			$meta_information->delete();

	}

}