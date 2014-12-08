<?php

namespace xAi;

class Model_Reception extends \Model_Table{

	public $session=null;
	public $data=null;
	public $informations=null;
	public $knowledge=null;
	public $new_session =false;
	public $fuzzy_mind = false;

	function init(){
		parent::init();

		$session_model =$this->add('xAi/Model_Session');
		$session_model->addCondition('name',session_id());
		$session_model->tryLoadAny();
		
		if(!$session_model->loaded()){
			$this->new_session = true;
			$this->initMind();
		}

		$session_model->save();
		$this->api->memorize('my_session',$session_model);

		

		$this->session = $this->api->memorize('my_session',$this->api->recall('my_session',$session_model));
		$this->data = $this->session->ref('xAi/Data')->getRows();

		$this->saveMind();
	}

	function sense(){
		// echo session_id() .'<br/>';

		$utlity = $this->add('xAi/Controller_Utility');
		$sensors = array('ALWAYS'=>array('ALWAYS'=>'RUN'),'SERVER'=>$_SERVER,'GET'=>$_GET,'POST'=>$_POST,'COOKIE'=>$_COOKIE);

		
		$meta_data = $this->add('xAi/Model_MetaData')->getRows();

		$data_record = array();

		// sense as per metadata settingsns and
		foreach ($sensors as $sensor_name => $var) {
			foreach ($var as $key => $value) {
				// If key not in metadata : save it
				if(!($settings = $utlity->existsInDataRow($meta_data,$key))){

					$new_meta_data = $this->add('xAi/Model_MetaData');
					$new_meta_data['from'] = $sensor_name;
					$new_meta_data['name'] = $key;
					$new_meta_data['last_value'] = is_array($value)?json_encode($value):$value;
					if($sensor_name=='ALWAYS') $new_meta_data['action'] = 2 ;// always
					$new_meta_data->save();
					$new_meta_data->destroy();
				}
				// If disgard this data : continue
				if($settings['action'] <= 0) continue;

				// If record everytime
					// record this data
					// continue
				if($settings['action'] == 2){ // Save always
					$data_record[$sensor_name][$key]['meta_data_id'] = $settings['id'];
					$data_record[$sensor_name][$key]['value'] = $value;
					continue;
				}

				// If First time in this session save 
				// print_r($this->data);
				if(!$utlity->existsKeyInData($this->data,$sensor_name,$key)){
					$data_record[$sensor_name][$key]['meta_data_id'] = $settings['id'];
					$data_record[$sensor_name][$key]['value'] = $value;
				}

			}
		}

		// save data to fetch information
		$data_record_model = $this->add('xAi/Model_Data');
		if(count($data_record)){
			$data_record_model['session_id'] = $this->session->id;
			$data_record_model['name'] = json_encode($data_record);
			$data_record_model->save();
		}else{
			// echo "nothing to save";
		}

		return $data_record_model;

	}


	function fetchInformation($senced_data_model){
		if(!$senced_data_model['name']) return;

		foreach (json_decode($senced_data_model['name'],true) as $sensor_name) {
			foreach ($sensor_name as $key=>$dt) {
				$current_data_value = $dt['value'];
				
				$meta_data_of_this_data = $this->add('xAi/Model_MetaData')->tryLoad($dt['meta_data_id']);
				if(!$meta_data_of_this_data->loaded()){
					throw new \Exception("For Debug Purpose", 1);
				}

				$extractors = $meta_data_of_this_data->ref('xAi/Model_InformationExtractor')->setOrder('order');
				$extractors->addCondition('is_active',true);
				
				foreach ($extractors as $junk) {
					// extract information 
					$result = null;
					eval($extractors['code']);
					if($result === null) continue;
					// $result should the available info by the code
					$this->informations[$extractors['name']] = $result;
					// handle repetations
					$information_type = $this->add('xAi/Model_MetaInformation');
					$information_type->addCondition('name',$extractors['name']);
					$information_type->tryLoadAny();
					
					if(!$information_type->loaded())
						$information_type->save();

					$existing_information = $this->add('xAi/Model_Information');
					$existing_information->addCondition('session_id',$this->session->id);

					switch ($extractors['repetation_handler']) {
						case 'increase_weight_for_same_key_value':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->addCondition('value',$result);
							$existing_information->tryLoadAny();
							$existing_information['weight'] = $existing_information['weight']+1;
							$existing_information['data_id']= $senced_data_model->id;
							$existing_information->saveAndUnload();
							break;
						case 'add_if_new_value':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->addCondition('value',$result);
							$existing_information->tryLoadAny();
							$existing_information['data_id']= $senced_data_model->id;
							$existing_information->saveAndUnload();
							break;
						case 'always_increase_weight':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->tryLoadAny();
							$existing_information['weight'] = $existing_information['weight']+1;
							$existing_information['data_id']= $senced_data_model->id;
							$existing_information->saveAndUnload();
							break;
						case 'discard_same_information':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->addCondition('value',$result);
							$existing_information->tryLoadAny();
							if(!$existing_information->loaded())
								$existing_information->saveAndUnload();
							break;
						case 'discard_if_repeated_key':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->tryLoadAny();
							if(!$existing_information->loaded()){
								$existing_information['value'] = $result;
								$existing_information['data_id']= $senced_data_model->id;
								$existing_information->saveAndUnload();
							}
							break;
						case 'update_last_value':
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information->tryLoadAny();
							$existing_information['value'] = $result;
							$existing_information['data_id']= $senced_data_model->id;
							$existing_information->saveAndUnload();
							break;

						case 'always_add':
						default:
							$existing_information->addCondition('meta_information_id',$information_type->id);
							$existing_information['value'] = $result;
							$existing_information['data_id']= $senced_data_model->id;
							$existing_information->saveAndUnload();
							break;
					}

				}

			}
		}
	}

	function graspKnowledge(){

	}

	function graspFeeling(){

	}

	function act(){

	}

	function think(){

	}

	function report(){

	}

	function explain($task){

	}

	function initMind(){
		$this->api->memorize('Ai_Fuzzy_Logic',false);
		$this->api->memorize('ai_rules',array());
		$this->api->memorize('ai_real_inputs',array());
		$this->fuzzy_mind = $this->api->memorize('fuzzy_mind',new Fuzzy_Logic());
	}

	function getAiRules(){
		return $this->api->recall('ai_rules',array());
	}

	function getAiRealInputs(){
		return $this->api->recall('ai_real_inputs',array());
	}

	function saveMind($fuzzy = null){
		if($fuzzy !== null and !($fuzzy instanceof Fuzzy_Logic)){
			$this->exception('Fuzzy Mind Must be of Fuzzy_Logic class');
		}

		if($fuzzy){
			$this->fuzzy_mind = $fuzzy;
			$this->api->memorize('fuzzy_mind', $this->fuzzy_mind);
			return;			
		}

		$this->fuzzy_mind = $this->api->memorize('fuzzy_mind',$this->api->recall('fuzzy_mind',new Fuzzy_Logic()));
	}

	function addRulesToMind(){
		$x= $this->getMind();
		if(!$x) return ;

		$rules = $this->api->recall('ai_rules',array());
		foreach ($rules as $rule) {
			if(!in_array($rule, $x->getRules()?$x->getRules(): array() ))
				$x->addRule($rule);
		}

		$this->saveMind();
	}

	function getMind(){
		return $this->fuzzy_mind;
	}

}