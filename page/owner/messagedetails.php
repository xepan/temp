<?php
class page_owner_messagedetails extends page_base_owner{
	function init(){
		parent::init();

		if($_GET['message_id']){
			$msg=$this->add('Model_Messages')->load($_GET['message_id']);
		}
		$v = $this->add('View_Info')->set('Message Detail');		
		$this->add('View')->set('Name - '.$msg['name']);
		$this->add('View')->set('From - '.$msg['sender_signature']);
		$this->add('View')->set('Message - '.$msg['message']);

	}
}		