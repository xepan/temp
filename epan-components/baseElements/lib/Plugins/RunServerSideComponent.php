<?php

namespace baseElements;


class Plugins_RunServerSideComponent extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('content-fetched',array($this,'Plugins_RunServerSideComponent'));
	}

	function Plugins_RunServerSideComponent($obj, $page){
		include_once (getcwd().'/lib/phpQuery.php');
		$pq = new \phpQuery();
		$doc = $pq->newDocument($page['content']);
		
		$server = $doc['[data-is-serverside-component=true]'];
		foreach($doc['[data-is-serverside-component=true]'] as $ssc){
			$options = array();
			foreach ($ssc->attributes as $attrName => $attrNode) {
    			$options[$attrName] = $pq->pq($ssc)->attr($attrName);
			}

			$namespace =  $pq->pq($ssc)->attr('data-responsible-namespace');
			$view =  $pq->pq($ssc)->attr('data-responsible-view');
			if(!file_exists($path = getcwd().DS.'epan-components'.DS.$namespace.DS.'lib'.DS.'View'.DS.'Tools'.DS.str_replace("View_Tools_", "", $view) .'.php'))
				$temp_view = $this->owner->add('View_Error')->set("Server Side Component Not Found :: $namespace/$view");
			else{
				$temp_view = $this->owner->add("$namespace/$view",array('html_attributes'=>$options,'data_options'=>$pq->pq($ssc)->attr('data-options')));
			}
			if(!$_GET['cut_object'] and !$_GET['cut_page']){
				$html = $temp_view->getHTML();
				$pq->pq($ssc)->html("")->append($html);
			}
		}
		$page['content'] = $doc->htmlOuter();
	}
}
