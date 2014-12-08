<?php

namespace xAi;


class Plugins_GoalAchieved extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('goal',array($this,'Plugins_GoalAchieved'));
	}

	function Plugins_GoalAchieved($obj, $goal){

		$session = $this->add('Ai/Model_Session');
		$session->tryLoadBy('name',session_id());

		if(!$session->loaded()) exit;

		$record =$this->add('Ai/Model_Record');
		$parent_block=0;

		$record['ib_id'] = $parent_block;
		$record['dimension_id'] = 0;
		$record['session_id'] = $session->id ;
		$record['event'] = 'Goal';
		$record['value'] = $goal['uuid'];

		$record->save();

		$session['any_goal_achieved']=true;
		$session['goals'] = $session['goals'].'~'.$goal['uuid'];
		$session->save();
	}
}
