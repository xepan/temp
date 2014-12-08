<?php

class page_owner_alert extends page_base_owner{
	function page_index(){
		
		$default_alert = $this->api->current_website->ref('Alerts')->addCondition('type','default')->count()->getOne();		
		$dv = $this->add('View_BackEndView',array('cols_widths'=>array(12)));
		$dv->addToTopBar('View')->setHTML('Default -'.$default_alert)->addClass('label label-default');

		$primary_alert = $this->api->current_website->ref('Alerts')->addCondition('type','primary')->count()->getOne();		
		$dv->addToTopBar('View')->setHTML('Primary -'.$primary_alert)->addClass('label label-primary');

		$success_alert = $this->api->current_website->ref('Alerts')->addCondition('type','success')->count()->getOne();		
		$dv->addToTopBar('View')->setHTML('Success -'.$success_alert)->addClass('label label-success');

		$info_alert = $this->api->current_website->ref('Alerts')->addCondition('type','info')->count()->getOne();		
		$dv->addToTopBar('View')->setHTML('Info -'.$info_alert)->addClass('label label-info');


		$warning_alert = $this->api->current_website->ref('Alerts')->addCondition('type','warning')->count()->getOne();		
		$dv->addToTopBar('View')->setHTML('Warning -'.$warning_alert)->addClass('label label-warning');


		$danger_alert = $this->api->current_website->ref('Alerts')->addCondition('type','danger')->count()->getOne();		
		$dv->addToTopBar('View')->setHTML('Danger -'.$danger_alert)->addClass('label label-danger');

		$dv_op = $dv->addOptionButton();
		$crud = $dv->addToColumn(0,'View');
		


		$alrt=$this->add('Model_Alerts');
		$alrt->addCondition('is_read',false);
		foreach ($alrt as  $junk) {
			// throw new \Exception("Error Processing Request", 1);
			
			$alrt['is_read']=true;
			$alrt->saveAndUnload();
		}
		$crud=$this->add('CRUD',array('allow_add'=>false,'allow_edit'=>false));
		$crud->setModel('Alerts',array('name','created_at','is_read','type','sender_signature'));
		if($crud->grid){
			$crud->grid->addQuickSearch(array('name','created_at','type','sender_signature'));
		}

	}	
}