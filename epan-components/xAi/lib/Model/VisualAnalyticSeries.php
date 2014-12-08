<?php

namespace xAi;

class Model_VisualAnalyticSeries extends \Model_Table {
	public $table = 'xai_visual_analytic_series';

	function init(){
		parent::init();

		$this->hasOne('xAi/MetaInformation','meta_information_id');
		$this->hasOne('xAi/VisualAnalytic','visual_analytic_id');
		$this->addField('name')->caption('Data Type')->setValueList(array('VALUE'=>'VALUE','COUNT'=>"COUNT",'SUM'=>'SUM','WEIGHTSUM'=>'WEIGHT SUM'));

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

}