<?php

namespace xAi;


class Plugins_ImplementIntelligence extends \componentBase\Plugin {

	public $executive;

	function init(){
		parent::init();
		$this->addHook('beforeTemplateInit',array($this,'Plugins_ImplementIntelligence'));
		$this->addHook('content-fetched',array($this,'Plugins_SelectIBlock'));
	}

	function Plugins_ImplementIntelligence($obj){

		if($_GET['bypass_intelligency']){
			$this->api->stickyGET('bypass_intelligency');
			return;
		}

		if(strpos($this->api->page, 'owner') !== false or strpos($this->api->page, 'install') !== false ) return;

		$this->executive = $executive = $this->add('xAi/Model_SalesExecutive');
		$sensed_data = $executive->sense();
		$executive->fetchInformation($sensed_data);
		// $executive->think();
		if($executive->new_session){
			$executive->initTriggerBasedThinking();
		}
		
		$executive->addRulesToMind();
		$executive->addRealInputValuesToFuzzyLogic();

		$x = $executive->getMind();
		

		if(count($x->getRules()))
			$this->api->memorize('iblock_weights',$x->calcFuzzy());
	}


  	function Plugins_SelectIBlock($obj,&$page){

  		if(strpos($this->api->page, 'owner') !== false or strpos($this->api->page, 'install') !== false ) return;

  		$x= $this->executive->getMind();

  // 		echo "<pre>";
		
		// echo "Input Names <br/>";
		// foreach ($x->getInputNames()?:array() as $in) {
		// 	echo $in . '<br/>';
		// 	print_r($x->getMembers($in));
		// }

		// echo "Output Names <br/>";
		// foreach ($x->getOutputNames()?:array() as $out) {
		// 	echo $out . '<br/>';
		// 	print_r($x->getMembers($out));
		// }

		// echo "RULES <br/>";
		// print_r($x->getRules());

		// echo "Real Inputs <br/>";
		// print_r($x->FRealInput);
		
		// echo "Calculated Outputs <br/>";
		// if(count($x->getRules()))
		// 	print_r($this->api->memorize('iblock_weights',$x->calcFuzzy()));
		
		// echo "</pre>";

  		$this->app->pathfinder->base_location->addRelativeLocation(
		    'epan-components/'.__NAMESPACE__, array(
		        'php'=>'lib',
		        'template'=>'templates',
		        'css'=>'templates/css',
		        'js'=>'templates/js',
		    )
		);
		
  // 		$l=$this->api->locate('addons',__NAMESPACE__, 'location');
		// $this->api->pathfinder->addLocation(
		// 	$this->api->locate('addons',__NAMESPACE__),
		// 	array(
		//   		'template'=>'templates',
		//   		'css'=>'templates/css',
		//   		'js'=>'templates/js'
		// 		)
		// 	)->setParent($l);
		$this->js(true)->_load('jquery.inview')->_load('jquery.expander.min')->_load('aitrack')->univ()->initaitrack();

		include_once (getcwd().'/lib/phpQuery.php');
		$pq = new \phpQuery();
		$doc = $pq->newDocument(trim($page['content']));

		// remove inner Iblocks first 
		foreach ($doc['[component_type=IntelligentBlock] [component_type=IntelligentBlock]'] as $inners) {
			$pq->pq($inners)->remove();
		}

		$doc= $pq->newDocument($doc->htmlOuter());

		foreach($doc['[component_type=IntelligentBlock]'] as $ib ){
			// foreach ib
				// process block
			$model = $this->add('xAi/Model_IBlockContent');
                        // echo "<br/>I is ". $i . '<br/>';
                        // echo(serialize($ib));
                        // if($i==5)break;
                        // $i++;
			$iblock = $pq->pq($ib);
			if(!$this->api->recall($iblock->attr('id'),false) OR !($this->add('xAi/Model_Config')->tryLoadAny()->get('keep_site_constant_for_session'))){
				// $model->setOrder('weight');
				$model->addCondition('iblock_id',$iblock->attr('id'));
				$model->implement_logic = true;
				$model->tryLoadAny();
				$this->api->memorize($iblock->attr('id'),$model->id);
			}else{
				$model->load($this->api->recall($iblock->attr('id')));	
			}

			$iblock->html(trim($model['content']));
			$iblock->attr('data-dimension-id',$model['dimension_id']);
		}
                // echo "</pre>";
                // if($i!=1) exit;
		$page['content'] = $doc->htmlOuter();

	}

}
