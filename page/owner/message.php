<?php

class page_owner_message extends page_base_owner{
	function page_index(){
		$bv = $this->add('View_BackEndView',array('cols_widths'=>array(12)));
		$bv->addToTopBar('H3')->set('Messages');
		
		$op = $bv->addOptionButton();
		$crud = $bv->addToColumn(0,'View');

		$msg=$this->add('Model_Messages');
		$msg->addCondition('is_read',false);
		foreach ($msg as  $junk) {
			// throw new \Exception("Error Processing Request", 1);
			$msg['is_read']=true;
			$msg->saveAndUnload();
		}
		$crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false));
		$crud->setModel('Messages',array('name','message','created_at',
										'is_read','sender_signature','watch'));
		
		if($crud->grid){
			$crud->grid->addQuickSearch(array('name','message','created_at'));
			$watch = $crud->grid->addColumn('Button','watching');
			$message_title = $crud->grid->setFormatter('name','template')
			->setTemplate('<a href="#" onclick="javascript:$(this).univ().frameURL(\'Message\',\'index.php?page=owner_messagedetails&message_id=<?$id?>\')"><?$name?></a>');	
		}
				
		
		if($_GET['watching']){			
			$msg_model=$this->add('Model_Messages')->load($_GET['watching']);
			if($msg_model['watch']==false)
				$msg_model['watch']=true;
			else
				$msg_model['watch']=false;
			$msg_model->save();
			$crud->grid->js(null,$this->js()->univ()->successMessage('Watch Changes'))->reload()->execute();
		}
	}	
}