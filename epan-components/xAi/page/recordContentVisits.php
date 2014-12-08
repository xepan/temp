<?php

// TODO - Optimize code for raw queries

class page_xAi_page_recordContentVisits extends Page  {
	
	function init(){
		parent::init();

		$this->executive = $executive = $this->add('xAi/Model_SalesExecutive');
		$sensed_data = $executive->sense();
		$executive->fetchInformation($sensed_data);
		
		$session = $executive->session;

		// TODO add information extraction as its needed by this module if not added well

		// $record =$this->add('xAi/Model_Record');
		// $record['page'] = $_GET['page'];
		// $record['subpage'] = $this->api->page_requested;

		// if($_POST['iblock_id']){
		// 	$current_ib_id = $this->add('Ai/Model_IBlockContent');
		// 	$current_ib_id->addCondition('iblock_id',$_POST['iblock_id']);
		// 	$current_ib_id->addCondition('dimension_id',$_POST['dimension_id']);
		// 	$current_ib_id = $current_ib_id->tryLoadAny()->get('id');
		// }
		// else
		// 	$current_ib_id=0;
		

		// $record['ib_id'] = $current_ib_id;
		// $record['dimension_id'] = $_POST['dimension_id']?:0;
		// $record['session_id'] = $session->id ;
		// $record['event'] = $_POST['eventname'] ;
		// $record['value'] = $_POST['value'] ;

		// $record->save();



		$this_blocks_session = $this->add('xAi/Model_Information',array('table_alias'=>'rec'));
		$session_j =  $this_blocks_session->join('xai_session','session_id');

		$this_blocks_session->addCondition('name','iblock');
		$this_blocks_session->addCondition('value',$executive->informations['iblock']);
		$this_blocks_session->_dsql()->del('fields')->field('GROUP_CONCAT(session_id) sessions');
		$this_blocks_session->_dsql()->del('fields')->field('session_id');
		$this_blocks_session->_dsql()->group('session_id');
		$this_blocks_session->_dsql()->order($session_j->table_alias.'.created_at','desc');
		$this_blocks_session->_dsql()->limit(100);

		$sessions = explode(",",$this_blocks_session->_dsql()->getOne());//->get('sessions');
		// echo $sessions;
		// exit;

		// Other blocks ON SAME PAGE particiapted where this iblock was presented

		$other_blocks = $this->add('xAi/Model_Information',array('table_alias'=>'rec'));
		$block_j = $other_blocks->join('xai_blocks','value');
		$block_j->addField('dimension_id');
		$block_j->addField('iblock_id');
		$block_j->addField('subpage');

		$other_blocks->addCondition('name','iblock');
		$other_blocks->addCondition('value','<>',$executive->informations['iblock']);
		$other_blocks->addCondition('session_id',$sessions);
		$other_blocks->_dsql()->del('fields')
					->field('value')
					->field('iblock_id')
					->field('dimension_id')
					->field('sum(weight) total_visibility')

					->group('value,dimension_id');
					;

		$rules = $this->api->recall('ai_rules',array());

		// Foreach IBlocks as ib/tt when this block was visible
		foreach ($other_blocks->_dsql() as $junk) {
			// tg = ib's count when goal was hit
			$other_block_count_in_goal = $this->add('xAi/Model_Information');
			$other_block_count_in_goal->addExpression('goal')->set(function($m,$q){
					return $m->refSQL('session_id')->fieldQuery('is_goal_achieved');
				});

			$other_block_count_in_goal->addCondition('name','iblock');
			$other_block_count_in_goal->addCondition('value',$junk['value']);
			$other_block_count_in_goal->addCondition('goal',1);
			$goal_count = $other_block_count_in_goal->count()->getOne();

			if($junk['iblock_id']){
				$new_rules = "IF IN".$this->api->normalizeName($_POST['iblock_id']).'DIM'.$_POST['dimension_id'].".Visible THEN OUT".$this->api->normalizeName($junk['iblock_id']).'DIM'.$junk['dimension_id'] .'.' . $this->executive->solveRatioToText($junk['total_visibility'],$goal_count) ;
				if(!in_array($new_rules, $rules)){
					$rules[] = $new_rules;
					$inputs = $this->api->recall('ai_real_inputs',array());
					$inputs['IN'.$this->api->normalizeName($_POST['iblock_id']).'DIM'.$_POST['dimension_id']]=50;
					$this->api->memorize('ai_real_inputs',$inputs);
				}
			}
		}

		$this->api->memorize('ai_rules',$rules);
		// $this->js()->univ()->successMessage("Recorded " . $_POST['iblock_id'])->execute();
		exit;
	}
}