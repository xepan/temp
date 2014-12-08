<?php
class View_Message extends CompleteLister{
	function init(){
		parent::init();

			$total_messages=$this->api->current_website->ref('Messages')->count()->getOne();
			$msg = $this->add('Model_Messages');
			$msg->addCondition(
	        	$msg->_dsql()->orExpr()
	            	->where('is_read',false)
	            	->where('watch',true)
	   		 );
			
			$new_messages=$msg->count()->getOne();
			$this->template->trySet('total_messages',$total_messages);
			$this->template->trySet('new_messages',$new_messages);
			$b=$this->add('Button',null,'viewInbox')->setHTML('View Inbox');
	}
	

	function defaultTemplate(){
		return array('owner/message');
	}
}