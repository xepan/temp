<?php

class page_xShop_page_owner_updatecontent extends page_componentBase_page_update {

	function init(){
		parent::init();


		$product_model = $this->add('xShop/Model_Product');
		// throw new \Exception("Error Processing Request".$_post['xshop_item_id']);
		// throw new \Exception(urldecode($_POST['body_html']), 1);
		
		$result = $product_model->updateContent($_POST['xshop_item_id'],urldecode(trim( $_POST['body_html'] ) ) );
		if($result == true){
			echo "updated";
		}else{
			echo "none";	
		}
		exit();
	}	
}		