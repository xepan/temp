<?php

class page_xShop_page_getproducttype extends Page{
	function init(){
		parent::init();
		
		$str= "<option value='0'>Please Select</option>";
		// $cats= $this->add('xShop/Model_Category');
		// foreach ($cats as $junk) {
		// 	$str.= "<option value='".$junk['id']."'>".$junk['name']."</option>";
		// }
		$str.= "<option value='all'>All Product</option>";
		$str.= "<option value='new'>New Products</option>";
		$str.= "<option value='feature'>Feature Product</option>";
		$str.= "<option value='latest'>Latest Product</option>";
		$str.= "<option value='mostviewed'>Most Viewed</option>";
		echo $str;
		exit;
	}
}