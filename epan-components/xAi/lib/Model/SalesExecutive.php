<?php

namespace xAi;

class Model_SalesExecutive extends Model_Reception {
	var $table= "xai_sales_executive";
	
	function init(){
		parent::init();

		$this->addField('name');

		//$this->add('dynamic_model/Controller_AutoCreator');
	}

	function initTriggerBasedThinking(){


		if(!$this->add('xAi/Model_Config')->tryLoadAny()->get('is_active') or $this->api->page != 'index' /*or $this->api->edit_mode*/)
			return;

		$this->addAllTriggerFactorsInInputName();
		$this->addAllIBlocksInInputOutPutNameAndWeightArray();


		$this->storeFactorBasedRules();
		$this->storeFactorBasedRealInputs();

		$this->saveMind();

  	}

  	function addAllTriggerFactorsInInputName(){
		// No Need to add all triggers as only current one needed

  		// meta_information with trigger to yes
  		$info = $this->add('xAi/Model_Information');
  		$meta_info_j = $info->join('xai_meta_information','meta_information_id');
  		$meta_info_j->addField('is_triggering');
	  	$info->addCondition('session_id',$this->session->id);	
  		$info->addCondition('is_triggering',true);
  		$info->_dsql()->group('meta_information_id');

  		$inp = array();
  		foreach ($info as $junk) {
  			$inp[$junk['name']] = $info['value'];
  		}

		$fuzzy_obj = $this->getMind();

		foreach ($inp as $input_name => $member) {
			$member = $input_name .'_' . crc32($member);
			$fuzzy_obj->setInputNames(array_merge(is_array($fuzzy_obj->getInputNames())?$fuzzy_obj->getInputNames():array(),array($input_name)));
			$fuzzy_obj->addMember($input_name,$member,  0, array(1,99), 100 ,TRAPEZOID);
		}
		return $fuzzy_obj;
	}

	function addAllIBlocksInInputOutPutNameAndWeightArray(){
		$rules = $this->api->recall('ai_rules');

		$fuzzy_obj = $this->getMind();

		$ibdims_array=array();
		foreach ($ibdim=$this->add('xAi/Model_IBlockContent') as $junk) {
			$ibdims_array[ $ibdim->id ] = array('prev_vote'=>0,'next_vote'=>0,'seen'=>0);

			$fuzzy_obj->setInputNames(array_merge($fuzzy_obj->getInputNames(),array('IN'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'])));
			
			$fuzzy_obj->addMember('IN'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'Visible',  0, 40, 60 ,LINFINITY);
			$fuzzy_obj->addMember('IN'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'Engaged',  40, 60, 100 ,RINFINITY);

			$fuzzy_obj->setOutputNames(array_merge(is_array($fuzzy_obj->getOutputNames())?$fuzzy_obj->getOutputNames(): array(),array('OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'])));
			
			$fuzzy_obj->addMember('OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'LOW',  0, 20, 40 ,LINFINITY);
			$fuzzy_obj->addMember('OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'MID',  30, 40, 50 ,TRIANGLE);
			$fuzzy_obj->addMember('OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'MUST',  40, 50, 60 ,TRIANGLE);
			$fuzzy_obj->addMember('OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'],'FUTURE',  60, 80, 100 ,RINFINITY);

			// $rules[] = "IF time.".('time_'.crc32($session_model->trigger['time'])). " THEN " . 'OUT'.$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'] .".MID";
		}

		$this->api->memorize('ai_rules',$rules);

		return $fuzzy_obj;
	}

	function storeFactorBasedRules(){
		$rules = $this->api->recall('ai_rules',array());
		// meta_information with trigger to yes
  		$info = $this->add('xAi/Model_Information');
  		$meta_info_j = $info->join('xai_meta_information','meta_information_id');
  		$meta_info_j->addField('is_triggering');
	  	$info->addCondition('session_id',$this->session->id);	
  		$info->addCondition('is_triggering',true);

  		$inp = array();
  		foreach ($info as $junk) {
  			$inp[$junk['name']] = $info['value'];
  		}

		foreach ($inp as $trigger => $value) {
			$original_value = $value;
			$value = $trigger .'_' . crc32($value);

			// all blocks that are included with trigger factor
			$included_blocks = $this->add('xAi/Model_Information',array('table_alias'=>'info'));
			$included_blocks->addExpression($trigger)->set(function($m,$q)use($trigger){
				$tmp_rec = $m->add('xAi/Model_Information',array('table_alias'=>'trigger_receiver'));
				$tmp_rec->addCondition('name',$trigger);
				$tmp_rec->addCondition('session_id',$q->getField('session_id'));
				return $tmp_rec->fieldQuery('value');
			});

			$included_blocks->addCondition($trigger, $original_value);
			$included_blocks->addCondition('name','iblock'); // This should be information name <==
			$included_blocks->join('xai_blocks','value');
			$included_blocks->addField('iblock_id');
			$included_blocks->addField('dimension_id');

			$included_blocks->_dsql()->del('fields')
					->field('sum(weight) block_count')
					->field('value')
					->field('iblock_id')
					->field('dimension_id')
					;

			$included_blocks->_dsql()->group('value,dimension_id');

			foreach ($included_blocks->_dsql() as $junk) {

				$included_blocks_in_goal = $this->add('xAi/Model_Information');
				$included_blocks_in_goal->addExpression($trigger)->set(function($m,$q)use($trigger){
					$tmp_rec = $m->add('xAi/Model_Information',array('table_alias'=>'trigger_receiver'));
					$tmp_rec->addCondition('name',$trigger);
					$tmp_rec->addCondition('session_id',$q->getField('session_id'));
					return $tmp_rec->fieldQuery('value');
				});

				$included_blocks_in_goal->addExpression('goal')->set(function($m,$q)use($trigger){
					return $m->refSQL('session_id')->fieldQuery('is_goal_achieved');
				});

				$included_blocks_in_goal->addCondition($trigger, $original_value);
				$included_blocks_in_goal->addCondition('value',$junk['value']);
				$included_blocks_in_goal->addCondition('goal',1);
				$goal_count = $included_blocks_in_goal->count()->getOne();

				if($junk['iblock_id'])
					$rules [] = "IF $trigger.$value THEN OUT".$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'] .'.' . $this->solveRatioToText($junk['block_count'],$goal_count) ;

			}

		}

		$this->api->memorize('ai_rules',$rules);

	}


	function storeFactorBasedRealInputs(){
		$inputs = $this->api->recall('ai_real_inputs',array());
		// meta_information with trigger to yes
  		$info = $this->add('xAi/Model_Information');
  		$meta_info_j = $info->join('xai_meta_information','meta_information_id');
  		$meta_info_j->addField('is_triggering');
	  	$info->addCondition('session_id',$this->session->id);	
  		$info->addCondition('is_triggering',true);

  		$inp = array();
  		foreach ($info as $junk) {
  			$inp[$junk['name']] = $info['value'];
  		}

		foreach ($inp as $factor => $value) {
			$value = $factor.'_' . crc32($value);
			$inputs[$factor]=50;
		}

		$this->api->memorize('ai_real_inputs',$inputs);
	}

	function addRealInputValuesToFuzzyLogic(){
		$x= $this->getMind();
		if(!$x) return ;

		$inputs = $this->api->recall('ai_real_inputs',array());
		foreach ($inputs as $factor => $value) {
			$x->setRealInput($factor, $value);
		}

		$this->saveMind();
	}	

	function solveRatioToText($total,$goaled){
		if($total == 0 ) return "LOW";
		$per = $goaled / $total * 100 ;
		if($per > 80) return "FUTURE";
		if($per > 60) return "MUST";
		if($per > 30) return "MID";
		if($per <= 30) return "LOW";
	}

}