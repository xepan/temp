<?php

namespace xAi;


class Plugins_BeforeSaveIBlockExtract extends \componentBase\Plugin {

	function init(){
		parent::init();
		$this->addHook('epan-page-before-save',array($this,'Plugins_BeforeSaveIBlockExtract'));
	}

	function Plugins_BeforeSaveIBlockExtract($obj, $page){
		// Get Outer IBlock and recursive till inside to save them all 
		// in SEPERATE BLOCKS
		
		include_once (getcwd().'/lib/phpQuery.php');
		$pq = new \phpQuery();
		$doc = $pq->newDocument(trim($page['content']));

		// include_once (getcwd().'/lib/phpQuery/phpQuery/phpQuery.php');
		// $doc = \phpQuery::newDocument($page['content']);

		foreach($doc['[component_type=IntelligentBlock]'] as $ib ){
			// remove all internal CONTENT of ib's leaving their div
			// save to existing or new (if not exists)

			$ib_model = $this->add('xAi/Model_IBlockContent');
			$ib_model->addCondition('iblock_id',$pq->pq($ib)->attr('id'));
			$ib_model->addCondition('parent_iblock_id',$pq->pq($ib)->parent('[component_type=IntelligentBlock]')->attr('id')?:null);
			$ib_model->addCondition('dimension_id', $pq->pq($ib)->attr('data-dimension-id'));

			$ib_model->tryLoadAny();

			$ib_model['content'] = $pq->pq($ib)->html();
			$ib_model->save();
		}

		// Change nothing .. Let every thing save.. we will pick best match on extraction time
		// $page['content'] = $doc->htmlOuter();
	}
}
