<?php

class page_xShop_page_ajaxhandler extends Page {

	function init(){
		parent::init();

		$task=$_GET['task'];

		if(!$task)
			throw new Exception("Must define task in query string", 1);
		call_user_method($task, $this);	
	}

	function page_removecartitem(){
		$cart_model=$this->add('xShop/Model_Cart');
		$cart_model->remove($_GET['cartitem_id']);		
		$this->js(null,$this->js()->_selector('.xshop-cart')->trigger('reload'))->univ()->successMessage("Item Removed From Cart")->execute();
	}

	function validateVoucher(){

		$voucher=$this->add('xShop/Model_DiscountVoucher');
		if(!$_GET['v_no']) return "";
		if(!$voucher->isUsable($_GET['v_no'])){
			$str="$('".$_GET['total_field']."').val() * 0 / 100";
			$dis="$('".$_GET['discount_field']."').val(0)";
			$dis.=";";
			$net="$('".$_GET['total_field']."').val() - $('".$_GET['discount_field']."').val()";
			$dis.="$('".$_GET['net_field']."').val($net)";
			$dis.=";";
			$dis.="$('".$_GET['form']."').atk4_form('fieldError','".$_GET['voucher_field']."','Not Valid')";			
			echo $dis; 
			exit;
		}else{			
			$str=$voucher->isUsable($_GET['v_no']);		
			$str="$('".$_GET['total_field']."').val() * '".$str."' / 100";
			$dis="$('".$_GET['discount_field']."').val($str)";
			$dis.=";";
			$net="$('".$_GET['total_field']."').val() - $('".$_GET['discount_field']."').val()";
			$dis.="$('".$_GET['net_field']."').val($net)";	
			
			echo $dis;
			exit;
		}

	}

}	