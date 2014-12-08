<?php

class page_xShop_page_search extends Page{
	function init(){
		parent::init();

		echo "Know more about Indian Products, launching soon. Come back later please. Checkout deshvikas.org for more";
		// exit;

		$msg= strtolower("DVS home");
		$pos=strpos(trim($msg), 'dvs');
		
		$product_model = $this->add('xShop/Model_Product');

		$product_model->addExpression('Relevance')->set('MATCH(search_string) AGAINST ("'.$search.'" IN BOOLEAN MODE)');
		$product_model->addCondition('Relevance','>',0);
		$product_model->join('xshop_manufacturer','manufacturer_id')->addField('manufacturer_name','name');

		$product_model->setLimit(5);
		// throw new \Exception($product_model['Relevance']);
 		$product_model->setOrder('Relevance','Desc');

 		$output= 'Indian Products found\n';

 		foreach ($product_model as $junk) {
 			$output .= $junk['name']. '('.$junk['manufacturer_name'].')\n';			
 		}

 		echo $output;
 		exit;

	}
}
