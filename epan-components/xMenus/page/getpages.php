<?php

class page_xMenus_page_getpages extends Page {
	function init(){
		parent::init();

		$epan_pages = $this->add('Model_EpanPage');
		$epan_pages->_dsql()->where('(parent_page_id = 0 or parent_page_id is null)');

		$li = $this->getPagesLi($epan_pages);
		echo $li;
		exit;
	}

	function getPagesLi($pages){
		$li='';
		foreach ($pages as $junk) {
			if($pages->ref('EpanPage')->count()->getOne() > 0){
				$li .= "<li class='dropdown'>";
				$li .= "<a href='#' class='dropdown-toggle' data-toggle='dropdown'>".$pages['menu_caption']."<b class='caret'></b></a>";
				$li .= "<ul class='dropdown-menu'>";
					$li .= $this->getPagesLi($pages->ref('EpanPage'));
				$li .= "</ul>";
				$li .= "</li>";
			}else{
				if($pages['menu_caption']){
					$li .= "<li>";
					$li .= "<a href='".$pages->generateURI()."'>";
						$li .= $pages['menu_caption'];
					$li .= "</a>";
					$li .= "</li>";
				}
			}
		}
		return $li;
	}
}
