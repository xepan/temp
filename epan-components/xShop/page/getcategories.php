<?php

class page_xShop_page_getcategories extends Page{
	function init(){
		parent::init();

		$catg_model= $this->add('xShop/Model_CategoryGroup');
		$str = "<option value='0'>Please Select</option>";
		foreach ($catg_model as $junk) {
			$str.= "<option value='".$junk['id']."'>".$junk['name']."</option>";
		}
		
		echo $str;
		exit;
	}
}