<?php

namespace xAi;


class View_DynamicChart extends \View{
	
	public $analytic = null;
	public $from_date=null;
	public $to_date=null;
	public $chart = null;
	public $information_model=null;

	function init(){
		parent::init();

		$this->setStyle('border','1px solid gray');

		if($this->from_date == null)
			$this->from_date = date("Y-m-d", strtotime(date("Y-m-d") . " -30 DAY"));

		if($this->to_date == null)
			$this->to_date = date("Y-m-d");

		$this->to_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($this->to_date)) . " +1 DAY")); 

		$this->chart = $ch = $this->add('chart/Chart');

		// Generate Data First
		$this->information_model = $information_model = $this->add('xAi/Model_Information');
		$session_j = $information_model->join('xai_session','session_id');
		$session_j->addField('created_at');
		$meta_info_j= $information_model->join('xai_meta_information','meta_information_id');

		$information_model->_dsql()->del('fields');
		$q= $information_model->dsql();

		switch ($this->analytic['group_by']) {
			case 'Hours':
				$information_model->_dsql()->field($q->expr('DATE_FORMAT('.$session_j->table_alias.'.created_at,"%H") group_by'));
				break;
			case 'Weeks':
				$information_model->_dsql()->field($q->expr('DATE_FORMAT('.$session_j->table_alias.'.created_at,"%U") group_by'));
				break;
			case 'Months':
				$information_model->_dsql()->field($q->expr('DATE_FORMAT('.$session_j->table_alias.'.created_at,"%M") group_by'));
				break;
			
			case 'Date':
			default:
				$information_model->_dsql()->field($q->expr('DATE('.$session_j->table_alias.'.created_at) group_by'));
				break;
		}


		$information_model->_dsql()->field($meta_info_j->table_alias.'.`name` info_name');

		// $or = $information_model->dsql()->orExpr();
		
		foreach ($series = $this->analytic->ref('xAi/VisualAnalyticSeries') as $junk) {
			// 	SUM(IF(_x_2.`name`= 'visit_count',VALUE,0)) visit_count,
			switch ($series['name']) {
				case 'VALUE':
					$information_model->_dsql()->field($q->expr('IF('.$meta_info_j->table_alias.'.`name`= "'.$series['meta_information'].'",value,0) `'.$series['meta_information'].'`'));
					break;
				case 'COUNT':
					$information_model->_dsql()->field($q->expr('COUNT(IF('.$meta_info_j->table_alias.'.`name`= "'.$series['meta_information'].'",value,0) ) `'.$series['meta_information'].'`'));
					break;
				case 'SUM':
					$information_model->_dsql()->field($q->expr('SUM(IF('.$meta_info_j->table_alias.'.`name`= "'.$series['meta_information'].'",value,0) ) `'.$series['meta_information'].'`'));
					break;
				case 'WEIGHTSUM':
					$information_model->_dsql()->field($q->expr('SUM(IF('.$meta_info_j->table_alias.'.`name`= "'.$series['meta_information'].'",weight,0) ) `'.$series['meta_information'].'`'));
					break;
			}
			// $or->where('info_name',$series['meta_information']);
		}
		
		$information_model->_dsql()->field($session_j->table_alias.'.created_at created_at');
		// $information_model->_dsql()->having($or);

		$information_model->_dsql()->where($session_j->table_alias.'.created_at','>=', $this->from_date);
		$information_model->_dsql()->where($session_j->table_alias.'.created_at','<', $this->to_date);
		
		$information_model->_dsql()->group('group_by');
		$information_model->setOrder('id');

		// $information_model->_dsql()->debug()->render();

		

		// $ch->debug();

	}

	function recursiveRender(){

		foreach ($this->information_model->_dsql() as $junk) {
			foreach ($series = $this->analytic->ref('xAi/VisualAnalyticSeries') as $series_junk) {
				// echo "adding ". $series['meta_information'] . ' under ' . $junk['group_by'] . ' = '. (int)$junk[$series['meta_information']] .' <br/>';
				$this->chart->addLineData($series['meta_information'],$junk['group_by'],(int)$junk[$series['meta_information']]);
			}
		}

		$this->chart
		->setXAxisTitle($this->analytic['group_by'])
		// ->setXAxis($xaxis)
		->setYAxisTitle($this->analytic['name'])
		->setTitle($this->analytic['chart_title'],null,$this->analytic['chart_sub_title'])
		->setChartType($this->analytic['visual_style'])
		// ->debug()
		;
		// ->setLegendsOptions(array("layout"=>"vertical","align"=>"right","verticalAlign"=>"top"));

		parent::recursiveRender();
	}

}