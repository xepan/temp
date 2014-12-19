<?php

namespace xShop;

class Grid_Item extends \Grid{
	function init(){
		parent::init();
		
		$this->add_sno();
		$this->addQuickSearch(array('sku','name','sale_price'));
		$this->addPaginator($ipp=100);
	}

	function recursiveRender(){

		$this->addColumn('expander','details');
		$this->addColumn('expander','categories');
		parent::recursiveRender();
	}

}