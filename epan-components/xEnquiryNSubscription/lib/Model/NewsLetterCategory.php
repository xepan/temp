<?php

namespace xEnquiryNSubscription;


class Model_NewsLetterCategory extends \Model_Table {
	public $table ='xEnquiryNSubscription_NewsLetterCategory';

	function init(){
		parent::init();
		
		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);

		$f=$this->addField('name')->mandatory(true)->group('a1~6~NewsLetter Category')->sortable(true)->display(array('grid'=>'shorttext'));
		$f->icon='fa fa-adn~red';

		$this->hasMany('xEnquiryNSubscription/NewsLetter','category_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		$jobs=$this->ref('xEnquiryNSubscription/EmailJobs');
		foreach($jobs as $junk){
			$jobs->delete();
		}

		$this->api->event('xenq_n_subs_newletter_before_delete',$this);

	}

}