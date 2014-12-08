<?php


namespace xAi;

class Model_VisualAnalytic extends \Model_Table{
	public $table ='xai_visual_analytic';

	function init(){
		parent::init();

		$this->addField('name')->caption('Anyalytic Factor');
		$this->addField('chart_title');
		$this->addField('chart_sub_title');

		$this->addField('visual_style')->setValueList(array('grid'=>'Grid','line'=>'Line','bar'=>'Bar','pie'=>'Pie'));
		$this->addField('use_gloabl_timespan')->type('boolean')->defaultValue(true);
		
		$this->addField('push_to_main_dashboard')->type('boolean')->defaultValue(false);
		$this->addField('main_dashboard_order')->type('int');
		$this->addField('span_on_main_dashboard')->type('int');
		
		$this->addField('push_to_analytic_dashboard')->type('boolean')->defaultValue(false);
		$this->addField('analytic_dashboard_order')->type('int');
		$this->addField('span_on_analytic_dashboard')->type('int');

		$this->addField('push_to_live_dashboard')->type('boolean')->defaultValue(false);
		$this->addField('live_dashboard_order')->type('int');
		$this->addField('span_on_live_dashboard')->type('int');


		$this->addField('group_by')->setValueList(array('Hours'=>'Hourly','Date'=>'Daily','Weeks'=>'Weakly','Months'=>'Monthly'));
		$this->hasOne('xAi/MetaInformation','grid_group_by_meta_information_id')->caption('Values Of');
		$this->addField('limit_top')->defaultValue(20);
		$this->addField('grid_value')->caption('Value Type')->setValueList(array('VALUE'=>'VALUE','COUNT'=>"COUNT",'SUM'=>'SUM','WEIGHTSUM'=>'WEIGHT SUM'));

		$this->hasMany('xAi/VisualAnalyticSeries','visual_analytic_id');

		$this->addHook('beforeDelete',$this);

		// $this->add('dynamic_model/Controller_AutoCreator');
	}

	function beforeDelete(){
		foreach($s=$this->ref('xAi/VisualAnalyticSeries') as $junk){
			$s->delete();
		}
	}


}