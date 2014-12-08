<?php

namespace xAi;

class Model_IBlockContent extends \Model_Table{
	public $table = 'xai_blocks';

	public $implement_logic=false;

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$this->hasOne('xAi/Dimension','dimension_id');

		$this->addField('iblock_id'); 
		$this->addField('parent_iblock_id');
		$this->addField('subpage')->defaultValue($this->api->page_requested); 
		
		// Content does not penetrats sub iblocks .. keeps it at their own by recursive functions at run time
		$this->addField('content')->type('text')->display(array('form'=>'RichText'));

		$this->addExpression('weight')->set(function($m,$q){
			return '("Most Critical to find")';
		});

		// $this->hasMany('Ai/Redord','ib_id');

		$this->addHook('beforeSave',$this);
		$this->addHook('afterLoad',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}


	function beforeSave(){

		include_once (getcwd().'/lib/phpQuery.php');
		$pq = new \phpQuery();
		$doc = $pq->newDocument(trim($this['content']));

		// include_once getcwd().'/lib/phpQuery.php';
		// $doc = \phpQuery::newDocument($this['content']);
		
		foreach($doc['[component_type=IntelligentBlock]'] as $ib ){
			// remove all internal CONTENT of ib's leaving their div
			// save to existing or new (if not exists)

			$ib_model = $this->add('xAi/Model_IBlockContent');
			$ib_model->addCondition('iblock_id',$pq->pq($ib)->attr('id'));
			$ib_model->addCondition('parent_iblock_id',$this['iblock_id']);
			$ib_model->addCondition('dimension_id', $pq->pq($ib)->attr('data-dimension-id'));

			$ib_model->tryLoadAny();

			$ib_model['content'] = $pq->pq($ib)->html();
			$ib_model->save();

		}

	}

	function afterLoad(){

		if(!$this->implement_logic) return;


		// Get the best dimenssion and proceed
		// and put the content in $this['content']
		
		// HOW >> get my id and fecth my ib_id
		$dimensions = $this->add('xAi/Model_IBlockContent');
		
		$dimensions->addCondition('iblock_id',$this['iblock_id']);
		$dimensions->addCondition('content','<>','');
		
		$fuzzy_weight_array = $this->api->recall('iblock_weights',array());

		// get all ids to ids_array now
		$ids_array=array();
		foreach ($dimensions as $junk) {
			for($i=0;$i <= $fuzzy_weight_array['OUT'.$this->api->normalizename($junk['iblock_id']).'DIM'.$junk['dimension_id']]; $i++)
				$ids_array[] = $dimensions->id;
		}
		// make an array
		$weigheted_array = $ids_array; // not default array(other wise non voted blocks will get missed)

		// shuffle array
		// ????

		// get random id from weigheted_array
		$my_pic = $weigheted_array[array_rand($weigheted_array)];

		// if none found then all random on ids_array
		if(!$my_pic) $ids_array[array_rand($ids_array)];
		// load its content in content
		if($my_pic)
			$dimensions->load($my_pic);
		else{
			$dimensions->tryLoadAny();
		}
		
		$this['content'] = $dimensions['content'];
		$this['dimension_id'] = $dimensions['dimension_id'];
		$this['iblock_id'] = $dimensions['iblock_id'];
		$this['parent_iblock_id'] = $dimensions['parent_iblock_id'];
		$this['subpage'] = $dimensions['subpage'];
		$this['id'] = $dimensions['id'];
		$this->id = $dimensions->id;
		$this->fuzzy_weight = $fuzzy_weight_array['OUT'.$this->api->normalizename($this['iblock_id']).'DIM'.$this['dimension_id']];



		include_once (getcwd().'/lib/phpQuery.php');
		$pq = new \phpQuery();
		$doc = $pq->newDocument($this['content']);
		
		// remove inner Iblocks first 
		foreach ($doc['[component_type=IntelligentBlock] [component_type=IntelligentBlock]'] as $inners) {
			$pq->pq($inners)->remove();
		}

		$doc= $pq->newDocument($doc->htmlOuter());


		// count non IB children as immediate
		$non_ib_children_found=$doc->children('[component_type!=IntelligentBlock]')->length();
		// foreach($doc['> [component_type!=IntelligentBlock]'] as $ib ){
		// 	$non_ib_children_found++;
		// }

		foreach($doc['[component_type=IntelligentBlock]'] as $ib ){
			$model = $this->add('xAi/Model_IBlockContent');
			if(!$this->api->recall($pq->pq($ib)->attr('id'),false) OR !($this->add('xAi/Model_Config')->tryLoadAny()->get('keep_site_constant_for_session'))){
				$model->addCondition('iblock_id',$pq->pq($ib)->attr('id'));
				$model->implement_logic=true;
				$model->tryLoadAny();
				$this->api->memorize($pq->pq($ib)->attr('id'),$model->id);
			}else{
				$model->load($this->api->recall($pq->pq($ib)->attr('id')));	
			}



			$pq->pq($ib)->html($model['content']);
			$pq->pq($ib)->attr('data-dimension-id',$model['dimension_id']);
		}

		// WHAT TO DO FOR SHUFFLE POSITIONS
		if(!$non_ib_children_found){
			// Sequences to be changed as per weight
			$self = $this;
			$temp=null;
			usort(iterator_to_array($doc['> [component_type=IntelligentBlock]']), function($a,$b)use($fuzzy_weight_array,$pq,$self, $doc, $temp){

				try{
					(int)$fuzzy_weight_array['OUT'.$self->api->normalizeName($pq->pq($a)->attr('id')).'DIM'.$pq->pq($a)->attr('data-dimension-id')] - (int)$fuzzy_weight_array['OUT'.$self->api->normalizeName($pq->pq($a)->attr('id')).'DIM'.$pq->pq($b)->attr('data-dimension-id')] > 0 ?
						// greater then 0
							$pq->pq($a)->prependTo($doc)
					:
						// less then or equal to zero
							$pq->pq($a)->appendTo($doc);
				}catch(\Exception $e){
				}
				return 0;				
			});
		}

		$this['content'] = $doc->htmlOuter();

	}
}