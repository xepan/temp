<?php

namespace xMarketingCampaign;


class Model_CampaignCategory extends \Model_Table {
	public $table ='xmarketingcampaign_campaigns_categories';

	function init(){
		parent::init();

		$this->hasOne('Epan','epan_id');
		$this->addCondition('epan_id',$this->api->current_website->id);
		$this->addField('name');

		$this->addExpression('campaigns')->set(function($m,$q){
			return $m->refSQL('xMarketingCampaign/Campaign')->count();
		});

		$this->hasMany('xMarketingCampaign/Campaign','category_id');

		$this->add('dynamic_model/Controller_AutoCreator');
	}
}