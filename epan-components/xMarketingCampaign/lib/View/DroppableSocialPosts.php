<?php

namespace xMarketingCampaign;

class View_DroppableSocialPosts extends \Grid{
	function setModel($model,$fields=array()){
		parent::setModel($model,$fields);
	}

	function recursiveRender(){
		$this->addFormatter('name','dropable');
		parent::recursiveRender();
	}

	function format_dropable($f){
		$this->current_row_html[$f] = '<div class="draggable-socialpost" data-event=\'{"title":"'.$this->model['name'].'", "_nid": '.$this->model->id.', "_eventtype": "SocialPost", "color":"#7a7"}\'>'.$this->current_row[$f].'</div>';
	}

	function render(){
		$this->js(true)->_selector('.draggable-socialpost')->draggable(array( 'helper'=> 'clone'));
		parent::render();
	}
}