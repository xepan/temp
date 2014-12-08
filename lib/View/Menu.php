<?php
class View_Menu extends View{
	function init(){
		parent::init();

		$component_list ="";

		$installed_components = $this->add('Model_InstalledComponents');
		$installed_components->addCondition('epan_id',$this->api->current_website->id);

		foreach ($installed_components as $junk) {
			$component_list .= '<li><a href="?page='.$junk['namespace'].'_page_owner_main"><i class="glyphicon glyphicon-play"></i> '.$junk['name'].'</a></li>';
		}

		$this->template->trySetHTML('conponents_list',$component_list);

		$msg=$this->add("Model_Messages");
		$msg->addCondition('epan_id',$this->api->current_website->id);
		//$msg->addCondition('is_read',false);
		$msg->_dsql()->having(
	        	$msg->_dsql()->orExpr()
	            	->where('is_read',false)
	            	->where('watch',true)
	   		 );
		
		$msg->tryLoadAny()->_dsql()->limit(3)->order('id','desc');

		$alt=$this->add("Model_Alerts");
		$alt->addCondition('epan_id',$this->api->current_website->id);
		$alt->addCondition('is_read',false);
		$alt->tryLoadAny()->_dsql()->limit(3)->order('id','desc');

		$this->add('View_Message',null,'message')->setModel($msg);
		$this->add('View_Alerts',null,'alert')->setModel($alt);

		$total_messages=$this->api->current_website->ref('Messages')->count()->getOne();
		$this->template->trySet('total_messages',$total_messages);
		$this->template->trySet('version',$this->api->getxEpanVersion());
		
	}

	function defaultTemplate(){
		return array('owner/menu');
	}
}