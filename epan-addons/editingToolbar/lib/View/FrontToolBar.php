<?php

namespace editingToolbar;
/**
 * This is frontend tool bar class render it self as well as tools
 */

class View_FrontToolBar extends \View{
	function init(){
		parent::init();
		
		// Get All Elements 
		$block_tabs = $this->add('Tabs',null,'tools');

		// $tool_block = $this->add('View',null,'tools')->addClass('block-title');
		// $tool_block->add('View')->set('Elements');
		$market_place = $this->add('Model_MarketPlace')->addCondition('type','element');
		
		$element_tab =$block_tabs->addTab('Elements');


		foreach ($market_place as $market_place_array) {
			$tools = $market_place->ref('Tools')->setOrder('order');
			
			if( ! $this->api->edit_template){
				$tools->addCondition('name','not like','%template%');
			}
			
			foreach ($tools as $cmp) {
				$element_tab->add('editingToolbar/View_Tool',array('namespace'=>$market_place['namespace'],'display_name'=>$tools['display_name']?:$tools['name'],'title'=>$tools['name'],'class'=>'View_Tools_'.str_replace("_", "", $this->api->normalizeName($tools['name'])),'is_serverside'=>$tools['is_serverside'],'is_sortable'=>$tools['is_sortable'],'is_resizable'=>$tools['is_resizable']));
			}
		}


		// Get All INSTALLED Modules and Applications
		$installed_components  = $this->api->current_website->ref('InstalledComponents');
		// TODO DELETE FOLLOWING LINE
		// $componenet_j = $installed_components->join('epan_components_marketplace','component_id');
		$installed_components->addCondition('has_toolbar_tools',true);

		foreach ($installed_components as $junk) {
			// TODO DELETE FOLLOWING LINE
			// $cmp = $installed_components->ref('component_id');
			$component_tab = $block_tabs->addTab($installed_components['name']);
			// $tool_block = $this->add('View',null,'tools')->addClass('block-title');
			// $tool_block->add('View')->set($installed_components['name']);
			$tools=$this->add('Model_Tools')->addCondition('component_id',$installed_components['component_id']);
			$tools->join('epan_components_marketplace','component_id')->addField('namespace');
			$tools->setOrder('order');
			foreach ($tools as $cmp) {
				$component_tab->add('editingToolbar/View_Tool',array('namespace'=>$tools['namespace'],'display_name'=>$tools['display_name']?:$tools['name'], 'title'=>$tools['name'],'class'=>'View_Tools_'.str_replace("_", "", $this->api->normalizeName($tools['name'])),'is_serverside'=>$tools['is_serverside'],'is_sortable'=>$tools['is_sortable'],'is_resizable'=>$tools['is_resizable']));
			}
		}

		$this->add('componentBase/View_CssOptions',null,'common_css_options')->js(true)->hide();
		$this->template->trySet('website_requested',$this->api->website_requested);
		$this->template->trySet('current_website_id',$this->api->current_website->id);
		$this->template->trySet('current_page_name',$this->api->current_page['name']);
		$this->template->trySet('current_page_id',$this->api->current_page->id);
		$this->template->trySet('current_template_id',$_GET['edit_template']?:$this->api->current_page['template_id']);
	}

	function defaultTemplate(){

		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-addons/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'css',
		        'js'=>'js',
		    )
		);

		return array('view/editingToolbar-toolbar');
	}
} 